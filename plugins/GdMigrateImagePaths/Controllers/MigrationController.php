<?php

/**
 * 迁移控制器
 *
 * 处理后台迁移工具的所有 HTTP 请求
 */

namespace Plugin\GdMigrateImagePaths\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Plugin\GdMigrateImagePaths\Services\MigrationService;
use Plugin\GdMigrateImagePaths\Services\ReportGenerator;

class MigrationController extends Controller
{
    private MigrationService $migrationService;

    private ReportGenerator $reportGenerator;

    public function __construct(
        MigrationService $migrationService,
        ReportGenerator $reportGenerator
    ) {
        $this->migrationService = $migrationService;
        $this->reportGenerator  = $reportGenerator;
    }

    /**
     * 显示主界面
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('GdMigrateImagePaths::admin.index');
    }

    /**
     * 扫描数据库
     *
     * @return JsonResponse
     */
    public function scan(): JsonResponse
    {
        try {
            $result = $this->migrationService->scan();

            return json_success(trans('GdMigrateImagePaths::migration.scan_success'), $result);
        } catch (\Exception $e) {
            return json_fail(trans('GdMigrateImagePaths::migration.scan_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * 预览迁移
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function preview(Request $request): JsonResponse
    {
        try {
            $fields = $request->input('fields', []);

            if (empty($fields)) {
                return json_fail(trans('GdMigrateImagePaths::migration.no_fields_selected'));
            }

            $previewData = $this->migrationService->preview($fields);

            return json_success(trans('GdMigrateImagePaths::migration.preview_success'), [
                'preview' => $previewData,
                'total'   => count($previewData),
            ]);
        } catch (\Exception $e) {
            return json_fail(trans('GdMigrateImagePaths::migration.preview_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * 执行迁移
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        try {
            $fields    = $request->input('fields', []);
            $batchSize = $request->input('batch_size', 1000);

            if (empty($fields)) {
                return json_fail(trans('GdMigrateImagePaths::migration.no_fields_selected'));
            }

            $report = $this->migrationService->execute($fields, $batchSize);

            return json_success(trans('GdMigrateImagePaths::migration.execute_success'), $report);
        } catch (\Exception $e) {
            return json_fail(trans('GdMigrateImagePaths::migration.execute_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * 验证迁移结果
     *
     * @return JsonResponse
     */
    public function verify(): JsonResponse
    {
        try {
            $result = $this->migrationService->verify();

            if ($result['success']) {
                return json_success(trans('GdMigrateImagePaths::migration.verify_success'), $result);
            }

            return json_success(trans('GdMigrateImagePaths::migration.verify_incomplete'), $result);

        } catch (\Exception $e) {
            return json_fail(trans('GdMigrateImagePaths::migration.verify_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * 导出报告
     *
     * @param Request $request
     * @param string  $reportId
     * @return \Illuminate\Http\Response
     */
    public function exportReport(Request $request, string $reportId)
    {
        try {
            $format = $request->input('format', 'json');
            $report = $this->reportGenerator->loadReport($reportId);

            if (! $report) {
                return json_fail(trans('GdMigrateImagePaths::migration.report_not_found'));
            }

            if ($format === 'json') {
                $content     = $this->reportGenerator->exportToJson($report);
                $filename    = "migration_report_{$reportId}.json";
                $contentType = 'application/json';
            } else {
                $content     = $this->reportGenerator->exportToText($report);
                $filename    = "migration_report_{$reportId}.txt";
                $contentType = 'text/plain';
            }

            return response($content)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } catch (\Exception $e) {
            return json_fail(trans('GdMigrateImagePaths::migration.export_failed') . ': ' . $e->getMessage());
        }
    }
}
