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

use Beike\Admin\Services\MarketingService;
use Beike\Repositories\PluginRepo;
use Illuminate\Http\Request;

class MarketingController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $filters = [
            'type'    => $request->get('type'),
            'keyword' => $request->get('keyword'),
        ];
        $plugins = MarketingService::getInstance()->getList($filters);
        $data    = [
            'plugins' => $plugins,
            'domain'  => str_replace(['http://', 'https://'], '', config('app.url')),
            'types'   => PluginRepo::getTypes(),
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
            $pluginCode = $request->code;
            $plugin     = MarketingService::getInstance()->getPlugin($pluginCode);
            $data       = [
                'domain' => str_replace(['http://', 'https://'], '', config('app.url')),
                'plugin' => $plugin,
            ];
            if ($request->expectsJson()) {
                return $data;
            }

            return view('admin::pages.marketing.show', $data);
        } catch (\Exception $e) {
            return redirect(admin_route('marketing.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * 下单购买插件
     */
    public function buy(Request $request)
    {
        try {
            $postData   = $request->getContent();
            $pluginCode = $request->code;
            $result     = MarketingService::getInstance()->buy($pluginCode, $postData);

            return json_success('获取成功', $result);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 下载插件安装包到本地
     */
    public function download(Request $request)
    {
        try {
            $pluginCode = $request->code;
            MarketingService::getInstance()->download($pluginCode);

            return json_success('下载解压成功, 请去插件列表安装');
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
