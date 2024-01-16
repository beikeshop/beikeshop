<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class Controller extends BaseController
{
    protected string $defaultRoute;

    /**
     * 表单页面获跳转页面链接
     * @return mixed
     */
    public function getRedirect()
    {
        if (empty($this->defaultRoute)) {
            $this->defaultRoute = $this->getDefaultRoute();
        }

        return request('_redirect') ?? request()->header('referer', admin_route($this->defaultRoute));
    }

    /**
     * 获取当前管理界面列表页路由
     * @return string
     */
    private function getDefaultRoute(): string
    {
        $currentRouteName = Route::getCurrentRoute()->getName();
        $names            = explode('.', $currentRouteName);
        $name             = $names[1] ?? '';

        return "{$name}.index";
    }

    /**
     * 导出CSV
     *
     * @param        $fileName
     * @param        $items
     * @param string $module
     * @return BinaryFileResponse
     * @throws \Exception
     */
    protected function downloadCsv($fileName, $items, string $module = ''): BinaryFileResponse
    {
        $module  = $module ?: $fileName;
        $charset = app()->getLocale() == 'zh-hk' ? 'BIG5' : 'GBK';

        if (empty($items)) {
            throw new \Exception(trans('common.empty_items'));
        }
        if (! str_contains($fileName, '.csv')) {
            $fileName = $fileName . '-' . date('YmdHis') . '.csv';
        }
        $headers = [
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'              => 'application/octet-stream',
            // 'Content-type' => 'text/csv',
            'Content-Disposition'       => "attachment; filename={$fileName}",
            'Content-Transfer-Encoding' => 'binary',
            'Expires'                   => '0',
            'Pragma'                    => 'public',
        ];

        $columns = array_keys($items[0]);
        foreach ($columns as $index => $column) {
            $columns[$index] = iconv('UTF-8', "{$charset}//IGNORE", trans("$module.{$column}"));
        }
        foreach ($items as $index => $item) {
            foreach ($item as $field => $value) {
                $items[$index][$field] = iconv('UTF-8', "{$charset}//IGNORE", $value);
            }
        }

        $filePath = storage_path('app/' . $fileName);
        $file     = fopen($filePath, 'w');
        fputcsv($file, $columns);
        foreach ($items as $item) {
            fputcsv($file, $item);
        }
        fclose($file);

        return response()->download($filePath, $fileName, $headers);
    }
}
