<?php

/**
 * 数据库扫描器
 *
 * 扫描数据库，识别包含图片路径的表和字段
 */

namespace Plugin\GdMigrateImagePaths\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Plugin\GdMigrateImagePaths\Services\PathHandlers\JsonPathHandler;
use Plugin\GdMigrateImagePaths\Services\PathHandlers\SerializedPathHandler;

class DatabaseScanner
{
    private JsonPathHandler $jsonHandler;

    private SerializedPathHandler $serializedHandler;

    /**
     * 系统表列表（需要排除）
     */
    private const SYSTEM_TABLES = [
        'migrations',
        'failed_jobs',
        'password_resets',
        'personal_access_tokens',
        'sessions',
    ];

    public function __construct()
    {
        $this->jsonHandler       = new JsonPathHandler;
        $this->serializedHandler = new SerializedPathHandler;
    }

    /**
     * 扫描数据库
     *
     * @return array 扫描结果
     */
    public function scan(): array
    {
        $textFields = $this->getTextFields();
        $results    = [];

        foreach ($textFields as $field) {
            $analysis = $this->analyzeField($field->TABLE_NAME, $field->COLUMN_NAME);

            if ($analysis['matchCount'] > 0) {
                $results[] = $analysis;
            }
        }

        // 特殊处理：强制扫描 settings 表的特定记录
        $settingsAnalysis = $this->scanSettingsTable();
        if ($settingsAnalysis && $settingsAnalysis['matchCount'] > 0) {
            // 检查是否已经在结果中
            $exists = false;
            foreach ($results as $result) {
                if ($result['table'] === 'settings' && $result['field'] === 'value') {
                    $exists = true;

                    break;
                }
            }
            if (! $exists) {
                $results[] = $settingsAnalysis;
            }
        }

        return [
            'tables'       => $this->groupByTable($results),
            'totalFields'  => count($results),
            'totalRecords' => array_sum(array_column($results, 'matchCount')),
        ];
    }

    /**
     * 特殊扫描 settings 表
     *
     * 扫描特定的 settings 记录，但只统计需要迁移的（包含 catalog/ 或 catalog\/ 但不包含 image/catalog/）
     *
     * @return array|null
     */
    private function scanSettingsTable(): ?array
    {
        // 检查 settings 表是否存在
        if (! Schema::hasTable('settings')) {
            return null;
        }

        // 查询特定的 settings 记录
        // 排除 logo, favicon, placeholder 这些不需要迁移的记录
        // 匹配 catalog/ 或 catalog\/ (JSON 转义格式)，但不包含 image/catalog/ 或 image\/catalog\/
        $query = DB::table('settings')
            ->whereIn('name', ['menu_setting', 'design_setting', 'footer_setting'])
            ->whereNotIn('name', ['logo', 'favicon', 'placeholder']);

        $records = $this->addPathFilters($query, 'value')->get();

        if ($records->isEmpty()) {
            return null;
        }

        // 获取样本数据
        $samples = $records->pluck('value')->take(3)->toArray();

        return [
            'table'      => 'settings',
            'field'      => 'value',
            'type'       => 'json',
            'matchCount' => $records->count(),
            'samples'    => $samples,
        ];
    }

    /**
     * 获取所有文本类型字段
     *
     * @return array
     */
    private function getTextFields(): array
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return DB::select("
                SELECT table_name AS \"TABLE_NAME\", column_name AS \"COLUMN_NAME\", data_type AS \"DATA_TYPE\"
                FROM information_schema.columns
                WHERE table_catalog = current_database()
                AND table_schema = current_schema()
                AND data_type IN ('character varying', 'text', 'json', 'jsonb')
                AND table_name NOT LIKE '%_backup_%'
            ");
        }

        $database = DB::connection()->getDatabaseName();

        return DB::select("
            SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ?
            AND DATA_TYPE IN ('varchar', 'text', 'mediumtext', 'longtext', 'json')
            AND TABLE_NAME NOT LIKE '%_backup_%'
        ", [$database]);
    }

    /**
     * 分析字段
     *
     * @param string $table 表名
     * @param string $field 字段名
     * @return array 分析结果
     */
    public function analyzeField(string $table, string $field): array
    {
        // 排除系统表
        if (in_array($table, self::SYSTEM_TABLES)) {
            return [
                'table'      => $table,
                'field'      => $field,
                'type'       => 'unknown',
                'matchCount' => 0,
                'samples'    => [],
            ];
        }

        // 特殊处理 settings 表的 value 字段
        // 只统计包含 catalog/ 或 catalog\/ 但不包含 image/catalog/ 的记录
        if ($table === 'settings' && $field === 'value') {
            // 排除 logo, favicon, placeholder 这些不需要迁移的记录
            $query = DB::table($table)
                ->whereIn('name', ['menu_setting', 'design_setting', 'footer_setting'])
                ->whereNotIn('name', ['logo', 'favicon', 'placeholder']);

            $records = $this->addPathFilters($query, $field)->get();

            if ($records->isEmpty()) {
                return [
                    'table'      => $table,
                    'field'      => $field,
                    'type'       => 'json',
                    'matchCount' => 0,
                    'samples'    => [],
                ];
            }

            $samples = $records->pluck('value')->take(3)->toArray();

            return [
                'table'      => $table,
                'field'      => $field,
                'type'       => 'json',
                'matchCount' => $records->count(),
                'samples'    => $samples,
            ];
        }

        // 其他表：同时支持 catalog/ 与 catalog\/ 两种格式
        $query = $this->addPathFilters(DB::table($table), $field);

        // 统计匹配记录数
        $matchCount = $query->count();

        if ($matchCount === 0) {
            return [
                'table'      => $table,
                'field'      => $field,
                'type'       => 'unknown',
                'matchCount' => 0,
                'samples'    => [],
            ];
        }

        // 获取样本数据
        $samples = (clone $query)
            ->limit(3)
            ->pluck($field)
            ->toArray();

        // 识别字段内容类型
        // 对于已知的 JSON 字段，强制识别为 JSON
        if ($this->isKnownJsonField($table, $field)) {
            $type = 'json';
        } else {
            $type = $this->identifyContentType($samples);
        }

        return [
            'table'      => $table,
            'field'      => $field,
            'type'       => $type,
            'matchCount' => $matchCount,
            'samples'    => $samples,
        ];
    }

    /**
     * 识别字段内容类型
     *
     * @param array $samples 样本数据
     * @return string 类型：plain, json, serialized
     */
    private function identifyContentType(array $samples): string
    {
        if (empty($samples)) {
            return 'plain';
        }

        $firstSample = $samples[0];

        // 检查是否是 JSON
        if ($this->jsonHandler->canHandle($firstSample)) {
            return 'json';
        }

        // 检查是否是序列化数据
        if ($this->serializedHandler->canHandle($firstSample)) {
            return 'serialized';
        }

        // 默认为纯文本
        return 'plain';
    }

    /**
     * 检查字段是否应该被识别为 JSON
     * 特殊处理某些已知的 JSON 字段
     *
     * @param string $table 表名
     * @param string $field 字段名
     * @return bool
     */
    private function isKnownJsonField(string $table, string $field): bool
    {
        // settings 表的特殊字段
        if ($table === 'settings' && $field === 'value') {
            return true;
        }

        // 可以在这里添加更多已知的 JSON 字段
        $knownJsonFields = [
            'settings.value',
            // 可以添加其他表.字段
        ];

        return in_array("{$table}.{$field}", $knownJsonFields);
    }

    /**
     * 按表分组结果
     *
     * @param array $results 扫描结果
     * @return array 按表分组的结果
     */
    private function groupByTable(array $results): array
    {
        $grouped = [];

        foreach ($results as $result) {
            $tableName = $result['table'];

            if (! isset($grouped[$tableName])) {
                $grouped[$tableName] = [
                    'name'         => $tableName,
                    'fields'       => [],
                    'totalRecords' => 0,
                ];
            }

            $grouped[$tableName]['fields'][] = [
                'name'       => $result['field'],
                'type'       => $result['type'],
                'matchCount' => $result['matchCount'],
                'samples'    => $result['samples'],
            ];

            $grouped[$tableName]['totalRecords'] += $result['matchCount'];
        }

        return array_values($grouped);
    }

    /**
     * 估算记录数
     *
     * @param string $table 表名
     * @param string $field 字段名
     * @return int 记录数
     */
    public function estimateRecordCount(string $table, string $field): int
    {
        return $this->addPathFilters(DB::table($table), $field)->count();
    }

    /**
     * 添加图片路径匹配条件
     *
     * @param mixed  $query 查询构造器
     * @param string $field 字段名
     * @return mixed
     */
    private function addPathFilters($query, string $field)
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            $column = $this->wrapColumn($field) . '::text';

            return $query
                ->where(function ($query) use ($column) {
                    $query->whereRaw("{$column} LIKE ?", ['%catalog/%'])
                        ->orWhereRaw("{$column} LIKE ?", ['%catalog\\\\/%']);
                })
                ->where(function ($query) use ($column) {
                    $query->whereRaw("{$column} NOT LIKE ?", ['%image/catalog/%'])
                        ->whereRaw("{$column} NOT LIKE ?", ['%image\\\\/catalog\\\\/%']);
                });
        }

        return $query
            ->where(function ($query) use ($field) {
                $query->where($field, 'LIKE', '%catalog/%')
                    ->orWhere($field, 'LIKE', '%catalog\\\\/%');
            })
            ->where(function ($query) use ($field) {
                $query->where($field, 'NOT LIKE', '%image/catalog/%')
                    ->where($field, 'NOT LIKE', '%image\\\\/catalog\\\\/%');
            });
    }

    /**
     * 包装字段名，避免 PostgreSQL 保留字或大小写问题
     *
     * @param string $field 字段名
     * @return string
     */
    private function wrapColumn(string $field): string
    {
        return DB::connection()->getQueryGrammar()->wrap($field);
    }
}
