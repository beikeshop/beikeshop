<?php

/**
 * 迁移服务
 *
 * 协调整个迁移流程
 */

namespace Plugin\GdMigrateImagePaths\Services;

use Illuminate\Support\Facades\Log;

class MigrationService
{
    private DatabaseScanner $scanner;

    private PathUpdater $updater;

    private ReportGenerator $reporter;

    public function __construct(
        DatabaseScanner $scanner,
        PathUpdater $updater,
        ReportGenerator $reporter
    ) {
        $this->scanner  = $scanner;
        $this->updater  = $updater;
        $this->reporter = $reporter;
    }

    /**
     * 扫描数据库
     *
     * @return array 扫描结果
     */
    public function scan(): array
    {
        Log::info('Starting database scan');

        try {
            $result = $this->scanner->scan();

            Log::info('Database scan completed', [
                'totalFields'  => $result['totalFields'],
                'totalRecords' => $result['totalRecords'],
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Database scan failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * 预览迁移
     *
     * @param array $fields 字段列表
     * @return array 预览数据
     */
    public function preview(array $fields): array
    {
        $previewData = [];

        foreach ($fields as $fieldInfo) {
            $analysis = $this->scanner->analyzeField(
                $fieldInfo['table'],
                $fieldInfo['field']
            );

            if (! empty($analysis['samples'])) {
                foreach ($analysis['samples'] as $sample) {
                    $previewData[] = [
                        'table'   => $fieldInfo['table'],
                        'field'   => $fieldInfo['field'],
                        'oldPath' => $sample,
                        'newPath' => $this->transformPreview($sample, $analysis['type']),
                    ];
                }
            }
        }

        return $previewData;
    }

    /**
     * 执行迁移
     *
     * @param array $fields    字段列表
     * @param int   $batchSize 批处理大小
     * @return array 迁移结果
     */
    public function execute(array $fields, int $batchSize = 1000): array
    {
        $migrationId = uniqid('migration_');
        $startTime   = microtime(true);

        Log::info('Starting migration', [
            'migration_id' => $migrationId,
            'fields_count' => count($fields),
        ]);

        try {
            // 执行更新
            $result = $this->updater->batchUpdate($fields, $batchSize);

            // 生成报告
            $report       = $this->reporter->generate($result, $startTime);
            $report['id'] = $migrationId;

            // 保存报告
            $reportPath = $this->reporter->saveReport($report);

            Log::info('Migration completed', [
                'migration_id' => $migrationId,
                'report_path'  => $reportPath,
                'summary'      => $result['summary'],
            ]);

            return $report;

        } catch (\Exception $e) {
            Log::error('Migration failed', [
                'migration_id' => $migrationId,
                'error'        => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * 验证迁移结果
     *
     * @return array 验证结果
     */
    public function verify(): array
    {
        Log::info('Starting migration verification');

        $scanResult = $this->scanner->scan();

        $result = [
            'success'          => $scanResult['totalRecords'] === 0,
            'remainingRecords' => $scanResult['totalRecords'],
            'remainingFields'  => $scanResult['totalFields'],
            'details'          => $scanResult['tables'],
        ];

        Log::info('Migration verification completed', $result);

        return $result;
    }

    /**
     * 预览路径转换
     *
     * @param string $content 内容
     * @param string $type    类型
     * @return string 转换后的内容
     */
    private function transformPreview(string $content, string $type): string
    {
        try {
            $handler = match ($type) {
                'json'       => new \Plugin\GdMigrateImagePaths\Services\PathHandlers\JsonPathHandler,
                'serialized' => new \Plugin\GdMigrateImagePaths\Services\PathHandlers\SerializedPathHandler,
                default      => new \Plugin\GdMigrateImagePaths\Services\PathHandlers\PlainTextPathHandler,
            };

            return $handler->transform($content);
        } catch (\Exception $e) {
            return '[转换失败: ' . $e->getMessage() . ']';
        }
    }
}
