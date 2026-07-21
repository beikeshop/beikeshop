<?php

/**
 * MarketingService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-09-26 11:50:34
 * @modified   2022-09-26 11:50:34
 */

namespace Beike\Admin\Services;

use Beike\Facades\BeikeHttp\Facade\Http;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarketingService
{
    public static function getInstance(): self
    {
        return new self;
    }

    /**
     * 获取可插件市场插件列表
     *
     * @param array $filters
     * @return mixed
     */
    public function getList(array $filters = []): mixed
    {
        $apiEndPoint = '/v1/plugins';

        return Http::sendGet($apiEndPoint);
    }

    /**
     * 获取插件市场单个插件信息
     *
     * @param $pluginCode
     * @return mixed
     */
    public function getPlugin($pluginCode): mixed
    {
        $apiEndPoint    = "/v1/plugins/{$pluginCode}";

        request()->query->add(['version' => config('beike.version')]);

        $plugin = Http::sendGet($apiEndPoint);

        return $plugin;
    }

    /**
     * Check plugin license
     *
     * @param $pluginCode
     * @param $domain
     * @return array|mixed
     */
    public function checkLicense($pluginCode, $domain): mixed
    {
        $apiEndPoint = "/v1/plugins/{$pluginCode}/license";

        request()->query->add(['domain' => $domain]);

        return Http::sendGet($apiEndPoint);
    }

    /**
     * 购买插件市场单个插件
     *
     * @throws Exception
     */
    public function buy($pluginCode, $postData)
    {
        $apiEndPoint = "/v1/plugins/{$pluginCode}/buy";

        $content = Http::sendPost($apiEndPoint, $postData);

        $status = $content['status'] ?? '';
        if ($status == 'success') {
            return $content['data'];
        }

        throw new Exception($content['message'] ?? '');
    }

    /**
     * 购买插件服务
     *
     * @throws Exception
     */
    public function buyService($pluginServiceId, $postData)
    {
        $apiEndPoint = "/v1/plugin_services/{$pluginServiceId}/buy";

        $content = Http::sendPost($apiEndPoint, $postData);

        $status = $content['status'] ?? '';
        if ($status == 'success') {
            return $content['data'];
        }

        throw new Exception($content['message'] ?? '');
    }

    /**
     * 获取插件服务订单信息
     *
     * @param $pluginServiceOrderId
     * @return mixed
     */
    public function getPluginServiceOrder($pluginServiceOrderId): mixed
    {
        $apiEndPoint = "/v1/plugin_services/{$pluginServiceOrderId}";

        request()->query->add(['version' => config('beike.version')]);

        $plugin      = Http::sendGet($apiEndPoint);

        if (empty($plugin)) {
            throw new NotFoundHttpException('该插件服务订单不存在或已下架');
        }

        return $plugin;
    }

    /**
     * 下载插件到网站
     *
     * @param            $pluginCode
     * @throws Exception
     */
    public function download($pluginCode): void
    {
        $download = new \Beike\Admin\Services\DownloadService;

        try {
            $download->download($pluginCode, 'oss');
        } catch (Exception $e) {
            try {
                $download->download($pluginCode, 'local');
            } catch (Exception $localEx) {
                throw new Exception($e->getMessage());
            }
        }
    }

    // getDomain
    public function getDomain($token)
    {
        $apiEndPoint = '/v1/website/get_domain';
        request()->query->add(['token' => $token]);

        return Http::sendGet($apiEndPoint);
    }

    // getToken
    public function getToken($domain)
    {
        $apiEndPoint = '/v1/website/get_token';
        request()->query->add(['domain' => $domain]);

        return Http::sendGet($apiEndPoint);
    }

    public function getLicensedPro($domain, $from)
    {
        $apiEndPoint = '/v1/licensed_pro';
        request()->query->add(['domain' => $domain, 'from' => $from]);

        return Http::sendGet($apiEndPoint);
    }

    // getToken
    public function checkToken($domain, $token)
    {
        $apiEndPoint = '/v1/website/check_token';
        request()->query->add(['domain' => $domain, 'token' => $token]);

        return Http::sendGet($apiEndPoint);
    }

    public function getVersionInfo($version)
    {
        $apiEndPoint = '/v1/version';
        request()->query->add(['version' => $version]);

        return Http::sendGet($apiEndPoint);
    }

    public function checkPluginVersion($pluginCodes)
    {
        $apiEndPoint = '/v1/plugins/version';
        request()->query->add(['fields' => $pluginCodes, 'throwException' => false]);

        return Http::sendGet($apiEndPoint);
    }

    public function toolSearch($search, $domain)
    {
        $apiEndPoint = '/v1/tool/plugin_search';
        request()->query->add(['search' => $search, 'domain' => $domain, 'timeout' => 5, 'throwException' => false]);

        return Http::sendGet($apiEndPoint);
    }

    public function checkPluginTicketExpired($pluginCode)
    {
        $apiEndPoint = '/v1/plugins/ticket_expired';
        request()->query->add(['plugin_code' => $pluginCode]);

        return Http::sendGet($apiEndPoint);
    }
}
