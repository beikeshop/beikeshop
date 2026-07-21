<?php

/**
 * 报告生成器
 *
 * 生成迁移报告
 */

namespace Plugin\GdMigrateImagePaths\Services;

use Illuminate\Support\Facades\Storage;

class ReportGenerator
{
    /**
     * 生成报告
     *
     * @param array $migrationResult 迁移结果
     * @param float $startTime       开始时间
     * @return array 报告数据
     */
    public function generate(array $migrationResult, float $startTime): array
    {
        $endTime  = microtime(true);
        $duration = round($endTime - $startTime, 2);

        $report = [
            'id'        => uniqid('migration_'),
            'startTime' => date('Y-m-d H:i:s', (int) $startTime),
            'endTime'   => date('Y-m-d H:i:s', (int) $endTime),
            'duration'  => $duration,
            'summary'   => $migrationResult['summary']           ?? [],
            'details'   => $migrationResult['details']           ?? [],
            'errors'    => $migrationResult['summary']['errors'] ?? [],
            'warnings'  => [],
        ];

        return $report;
    }

    /**
     * 保存报告到文件
     *
     * @param array $report 报告数据
     * @return string 文件路径
     */
    public function saveReport(array $report): string
    {
        $filename = 'migration_' . $report['id'] . '_' . date('YmdHis') . '.json';
        $path     = 'migrations/reports/' . $filename;

        Storage::put($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $path;
    }

    /**
     * 导出为 JSON
     *
     * @param array $report 报告数据
     * @return string JSON 字符串
     */
    public function exportToJson(array $report): string
    {
        return json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * 导出为文本格式
     *
     * @param array $report 报告数据
     * @return string 文本内容
     */
    public function exportToText(array $report): string
    {
        $text = "=== 图片路径迁移报告 ===\n\n";
        $text .= "报告 ID: {$report['id']}\n";
        $text .= "开始时间: {$report['startTime']}\n";
        $text .= "结束时间: {$report['endTime']}\n";
        $text .= "总耗时: {$report['duration']} 秒\n\n";

        $text .= "=== 统计摘要 ===\n";
        $summary = $report['summary'];
        $text .= "处理记录数: {$summary['totalProcessed']}\n";
        $text .= "更新记录数: {$summary['totalUpdated']}\n";
        $text .= "跳过记录数: {$summary['totalSkipped']}\n";
        $text .= "失败记录数: {$summary['totalFailed']}\n\n";

        if (! empty($report['details'])) {
            $text .= "=== 详细信息 ===\n";
            foreach ($report['details'] as $detail) {
                $text .= "\n表: {$detail['table']}, 字段: {$detail['field']}\n";
                $text .= "  处理: {$detail['totalProcessed']}\n";
                $text .= "  更新: {$detail['totalUpdated']}\n";
                $text .= "  跳过: {$detail['totalSkipped']}\n";
                $text .= "  失败: {$detail['totalFailed']}\n";
            }
        }

        if (! empty($report['errors'])) {
            $text .= "\n=== 错误信息 ===\n";
            foreach ($report['errors'] as $error) {
                $text .= "记录 ID: {$error['record_id']}, 错误: {$error['error']}\n";
            }
        }

        return $text;
    }

    /**
     * 读取报告
     *
     * @param string $reportId 报告 ID
     * @return array|null 报告数据
     */
    public function loadReport(string $reportId): ?array
    {
        $files = Storage::files('migrations/reports');

        foreach ($files as $file) {
            if (str_contains($file, $reportId)) {
                $content = Storage::get($file);

                return json_decode($content, true);
            }
        }

        return null;
    }
}
