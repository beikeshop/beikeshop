<?php

/**
 * 路径更新器
 *
 * 执行实际的路径更新操作
 */

namespace Plugin\GdMigrateImagePaths\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Plugin\GdMigrateImagePaths\Services\PathHandlers\JsonPathHandler;
use Plugin\GdMigrateImagePaths\Services\PathHandlers\PlainTextPathHandler;
use Plugin\GdMigrateImagePaths\Services\PathHandlers\SerializedPathHandler;

class PathUpdater
{
    private PlainTextPathHandler $plainHandler;

    private JsonPathHandler $jsonHandler;

    private SerializedPathHandler $serializedHandler;

    /**
     * 默认批处理大小
     */
    private const DEFAULT_BATCH_SIZE = 1000;

    public function __construct()
    {
        $this->plainHandler      = new PlainTextPathHandler;
        $this->jsonHandler       = new JsonPathHandler;
        $this->serializedHandler = new SerializedPathHandler;
    }

    /**
     * 更新表字段
     *
     * @param string $table     表名
     * @param string $field     字段名
     * @param string $type      字段类型（plain, json, serialized）
     * @param int    $batchSize 批处理大小
     * @return array 更新结果
     */
    public function updateField(
        string $table,
        string $field,
        string $type,
        int $batchSize = self::DEFAULT_BATCH_SIZE
    ): array {
        $result = [
            'table'          => $table,
            'field'          => $field,
            'totalProcessed' => 0,
            'totalUpdated'   => 0,
            'totalSkipped'   => 0,
            'totalFailed'    => 0,
            'errors'         => [],
        ];

        // 选择合适的处理器
        $handler = $this->getHandler($type);

        DB::beginTransaction();

        try {
            $offset = 0;

            while (true) {
                // 构建查询
                // 特殊处理 settings 表的 value 字段
                // 排除 logo, favicon, placeholder 这些不需要迁移的记录
                // 匹配 catalog/ 或 catalog\/ (JSON 转义格式)，但不包含 image/catalog/
                if ($table === 'settings' && $field === 'value') {
                    $query = DB::table($table)
                        ->whereIn('name', ['menu_setting', 'design_setting', 'footer_setting'])
                        ->whereNotIn('name', ['logo', 'favicon', 'placeholder']);

                    $records = $this->addPathFilters($query, $field)
                        ->limit($batchSize)
                        ->offset($offset)
                        ->get();
                } else {
                    // 其他表：同时支持 catalog/ 与 catalog\/ 两种格式
                    $records = $this->addPathFilters(DB::table($table), $field)
                        ->limit($batchSize)
                        ->offset($offset)
                        ->get();
                }

                if ($records->isEmpty()) {
                    break;
                }

                foreach ($records as $record) {
                    $result['totalProcessed']++;

                    try {
                        $oldValue = $record->$field;

                        // 跳过空值
                        if (empty($oldValue)) {
                            $result['totalSkipped']++;

                            continue;
                        }

                        // 转换路径
                        $newValue = $handler->transform($oldValue);

                        // 如果值没有变化，跳过更新
                        if ($oldValue === $newValue) {
                            $result['totalSkipped']++;

                            continue;
                        }

                        // 更新记录
                        DB::table($table)
                            ->where('id', $record->id)
                            ->update([$field => $newValue]);

                        $result['totalUpdated']++;

                    } catch (\Exception $e) {
                        $result['totalFailed']++;
                        $result['errors'][] = [
                            'record_id' => $record->id ?? null,
                            'error'     => $e->getMessage(),
                        ];

                        Log::error('Failed to update record', [
                            'table'     => $table,
                            'field'     => $field,
                            'record_id' => $record->id ?? null,
                            'error'     => $e->getMessage(),
                        ]);
                    }
                }

                $offset += $batchSize;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $result;
    }

    /**
     * 批量更新多个表字段
     *
     * @param array $fields    字段列表 [['table' => '', 'field' => '', 'type' => ''], ...]
     * @param int   $batchSize 批处理大小
     * @return array 更新结果
     */
    public function batchUpdate(array $fields, int $batchSize = self::DEFAULT_BATCH_SIZE): array
    {
        $results = [];
        $summary = [
            'totalProcessed' => 0,
            'totalUpdated'   => 0,
            'totalSkipped'   => 0,
            'totalFailed'    => 0,
            'errors'         => [],
        ];

        foreach ($fields as $fieldInfo) {
            $result = $this->updateField(
                $fieldInfo['table'],
                $fieldInfo['field'],
                $fieldInfo['type'],
                $batchSize
            );

            $results[] = $result;

            // 累加统计
            $summary['totalProcessed'] += $result['totalProcessed'];
            $summary['totalUpdated']   += $result['totalUpdated'];
            $summary['totalSkipped']   += $result['totalSkipped'];
            $summary['totalFailed']    += $result['totalFailed'];
            $summary['errors'] = array_merge($summary['errors'], $result['errors']);
        }

        return [
            'summary' => $summary,
            'details' => $results,
        ];
    }

    /**
     * 获取合适的处理器
     *
     * @param string $type 类型
     * @return PathHandlerInterface
     */
    private function getHandler(string $type)
    {
        return match ($type) {
            'json'       => $this->jsonHandler,
            'serialized' => $this->serializedHandler,
            default      => $this->plainHandler,
        };
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
