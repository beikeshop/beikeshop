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
        $plugins = MarketingService::getList();
        $data = [
            'plugins' => $plugins,
        ];
        return view('admin::pages.marketing.index', $data);
    }


    /**
     * 下载插件安装包到本地
     */
    public function download(Request $request)
    {
        $pluginCode = $request->code;
        $datetime = date('Y-m-d');
        $url = config('beike.api_url') . "/api/plugins/{$pluginCode}/download";
        $content = file_get_contents($url);

        $pluginPath = "plugins/{$pluginCode}-{$datetime}.zip";
        Storage::disk('local')->put($pluginPath, $content);

        $pluginZip = storage_path('app' . $pluginPath);
        dd($pluginZip);
    }
}
