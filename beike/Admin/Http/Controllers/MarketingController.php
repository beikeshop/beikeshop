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
use Exception;
use Illuminate\Http\Request;

class MarketingController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data    = [
            'same_domain' => check_same_domain(),
        ];

        return view('admin::pages.marketing.index', $data);
    }

    /**
     * 获取单个插件详情
     */
    public function show(Request $request)
    {
        $pluginCode = $request->code;
        $data       = [
            'domain'      => str_replace(['http://', 'https://'], '', config('app.url')),
            'plugin_code' => $pluginCode,
        ];

        return view('admin::pages.marketing.show', $data);
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
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 下单购买插件服务
     */
    public function buyService(Request $request)
    {
        try {
            $postData   = $request->getContent();
            $id         = $request->id;
            $result     = MarketingService::getInstance()->buyService($id, $postData);

            return json_success('获取成功', $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 获取单个插件详情
     */
    public function serviceOrder(Request $request)
    {
        try {
            $id                     = $request->id;
            $pluginServiceOrder     = MarketingService::getInstance()->getPluginServiceOrder($id);

            if ($request->expectsJson()) {
                return json_success('成功', $pluginServiceOrder);
            }
        } catch (Exception $e) {
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

            if ($request->get('type') == 'update'){
                app('plugin')->getPluginOrFail($pluginCode);
            }

            $plugin = MarketingService::getInstance()->getPlugin($pluginCode);

            if ($plugin && $plugin['data']['status'] == 'pending') {
                throw new Exception('plugin_pending');
            }

            MarketingService::getInstance()->download($pluginCode);

            if ($request->get('type') == 'update'){
                return json_success(trans('admin/marketing.update_success'));
            }

            return json_success(trans('admin/marketing.download_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    // 根据传入的 token 获取域名，然后判断是否是当前域名
    public function checkDomain(Request $request)
    {
        try {
            $token         = $request->token;
            $location_host = $request->location_host;
            $domain        = MarketingService::getInstance()->getDomain($token);
            if ($domain['data'] && (get_domain($domain['data']) !== get_domain($location_host))) {
                return json_success('fail', trans('admin/marketing.domain_token_domain_error', ['domain' => $location_host, 'token_domain' => $domain['data']]));
            }

            return json_success('获取成功', $domain);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    // 根据传入的域名获取 token
    public function getToken(Request $request)
    {
        try {
            $domain     = $request->domain;
            $result     = MarketingService::getInstance()->getToken($domain);

            return json_success('获取成功', $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function getLicensedPro(Request $request)
    {
        try {
            $domain     = $request->domain;
            $from       = $request->from;
            $result     = MarketingService::getInstance()->getLicensedPro($domain,$from);

            return json_success('获取成功', $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    // 根据传入的域名获取 token
    public function checkToken(Request $request)
    {
        try {
            $domain     = $request->domain;
            $token      = $request->token;
            $result     = MarketingService::getInstance()->checkToken($domain,$token);

            return json_success('获取成功', $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
