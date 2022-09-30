<?php
/**
 * MarketingController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-26 11:49:34
 * @modified   2022-09-26 11:49:34
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\PluginRepo;
use ZanySoft\Zip\Zip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Beike\Admin\Services\MarketingService;

class MarketingController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $filters = [
            'type' => $request->get('type'),
            'keyword' => $request->get('keyword'),
        ];
        $plugins = MarketingService::getList($filters);
        $data = [
            'plugins' => $plugins,
            'types' => PluginRepo::getTypes(),
        ];

        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.marketing.index', $data);
    }


    /**
     * 获取单个插件详情
     */
    public function show(Request $request)
    {
        try {
            $code = $request->code;
            $plugin = MarketingService::getPlugin($code);
            $data = [
                'plugin' => $plugin,
            ];
            return view('admin::pages.marketing.show', $data);
        } catch (\Exception $e) {
            return redirect(admin_route('marketing.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * 下载插件安装包到本地
     */
    public function download(Request $request)
    {
        try {
            $pluginCode = $request->code;
            $datetime = date('Y-m-d');
            $url = config('beike.api_url') . "/api/plugins/{$pluginCode}/download";
            $content = file_get_contents($url);

            $pluginPath = "plugins/{$pluginCode}-{$datetime}.zip";
            Storage::disk('local')->put($pluginPath, $content);

            $pluginZip = storage_path('app/' . $pluginPath);
            $zipFile = Zip::open($pluginZip);
            $zipFile->extract(base_path('plugins'));
            return json_success('下载解压成功, 请去插件列表安装');
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
