<?php
/**
 * MarketingService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-26 11:50:34
 * @modified   2022-09-26 11:50:34
 */

namespace Beike\Admin\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ZanySoft\Zip\Zip;
use ZipArchive;

class MarketingService
{
    private PendingRequest $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::withOptions([
            'verify' => false,
            'timeout' => 0,
            'connect_timeout' => 0,
        ])->withHeaders([
            'developer-token' => system_setting('base.developer_token'),
            'domain' => request()->getHost(),
            'v' => 1,
        ]);
    }

    public static function getInstance()
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
        $url = config('beike.api_url') . '/api/plugins?locale=' . (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en');
        if (! empty($filters)) {
            $url .= '&' . http_build_query($filters);
        }

        return $this->httpClient->get($url)->json();
    }

    /**
     * 获取插件市场单个插件信息
     *
     * @param $pluginCode
     * @return mixed
     */
    public function getPlugin($pluginCode): mixed
    {
        $url    = config('beike.api_url') . "/api/plugins/{$pluginCode}?version=" . config('beike.version') . '&locale=' . (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en');
        $plugin = $this->httpClient->get($url)->json();

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
        $url = config('beike.api_url') . "/api/plugins/{$pluginCode}/license?domain={$domain}";

        return $this->httpClient->get($url)->json();
    }

    /**
     * 购买插件市场单个插件
     *
     * @throws Exception
     */
    public function buy($pluginCode, $postData)
    {
        $url = config('beike.api_url') . "/api/plugins/{$pluginCode}/buy?" . 'locale=' . (admin_locale() == 'zh_cn' ? 'zh_cn' : 'en');

        $content = $this->httpClient->withBody($postData, 'application/json')
            ->post($url)
            ->json();

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
        $url = config('beike.api_url') . "/api/plugin_services/{$pluginServiceId}/buy";

        $content = $this->httpClient->withBody($postData, 'application/json')
            ->post($url)
            ->json();

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
        $url    = config('beike.api_url') . "/api/plugin_services/{$pluginServiceOrderId}?version=" . config('beike.version');
        $plugin = $this->httpClient->get($url)->json();

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
    public function download($pluginCode)
    {
        $datetime = date('Y-m-d');
        $url      = config('beike.api_url') . "/api/plugins/{$pluginCode}/download";

        $content = $this->httpClient->get($url, [
            'timeout' => null,
        ])->body();

        $pluginPath = "plugins/{$pluginCode}-{$datetime}.zip";
        Storage::disk('local')->put($pluginPath, $content);

        $pluginZip = storage_path('app/' . $pluginPath);

        $info = $this->getPluginInfo($pluginZip);

        //是否beikeshop 插件
        if ($info['is_beikeshop_plugin']) {

            try {
                $zipFile   = (new Zip)->open($pluginZip);

                if ($info['is_error']) {
                    $info2 = $info['dir_info'];

                    if ($info['error_dir']) {

                        //文件跟命名空间不符合的插件
                        if (count($info2) == 2) {
                            $dir   = $info2[1];
                            $jydir = base_path('plugins');
                            $zipFile->extract($jydir);

                            $error_dir = base_path('plugins/' . $info['error_dir']);
                            $ok_dir    = base_path('plugins/' . $dir);

                            $result = @rename($error_dir, $ok_dir);
                            if (! $result) {
                                throw new Exception('重命名插件文件夹失败');
                            }
                        }
                    } else {
                        //散开的文件
                        if (count($info2) == 2) {
                            $dir        = $info2[1];
                            $plugin_dir = base_path('plugins/' . $dir);

                            if (! is_dir($plugin_dir)) {
                                (new \Illuminate\Filesystem\Filesystem)->makeDirectory($plugin_dir);
                            }
                            $zipFile->extract($plugin_dir);
                        }
                    }

                } else {
                    $zipFile->extract(base_path('plugins')); //正常的beikeshop 插件
                }
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        } else {
            throw new Exception('无法识别的beikeshop插件！');
        }

    }

    // getDomain
    public function getDomain($token)
    {
        $url = config('beike.api_url') . '/api/website/get_domain?token=' . $token;

        return $this->httpClient->get($url)->json();
    }

    // getToken
    public function getToken($domain)
    {
        $url = config('beike.api_url') . '/api/website/get_token?domain=' . $domain;

        return $this->httpClient->get($url)->json();
    }

    /**
     *  get plugin dir by preg match content
     *
     * @param $content
     * @return string[]
     */
    public function getPluginDir($content)
    {
        preg_match('/namespace\s+([^\s;]+);/', $content, $matches);

        return explode('\\', $matches[1]);
    }

    /**
     *  get plugin info
     *
     * @throws Exception
     */
    public function getPluginInfo($zip_file)
    {
        $plugin_dir          = '';
        $dir_info            = [];
        $error_dir           = '';
        $is_error            = false;
        $is_beikeshop_plugin = false;

        $zip                 = new ZipArchive;

        if ($zip->open($zip_file) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];

                //是否为文件夹
                if (str_ends_with($name, '/')) {
                    $count = substr_count($name, '/');
                    if ($count == 1) {
                        $plugin_dir = $name;
                    }

                } else {
                    //文件
                    if ($name == 'Bootstrap.php') {
                        $content  = $zip->getFromIndex($i);
                        $dir_info = $this->getPluginDir($content);
                        $is_error = true;

                        break;
                    }

                    if ($name == $plugin_dir . 'Bootstrap.php') {
                        $content  = $zip->getFromIndex($i);
                        $dir_info = $this->getPluginDir($content);
                        if (count($dir_info) == 2) {
                            $_pluginDir = $dir_info[1];
                            if (rtrim($plugin_dir, '/') != $_pluginDir) {
                                $is_error  = true;
                                $error_dir = rtrim($plugin_dir, '/');
                            }
                        }

                        break;
                    }

                }
            }

            if (count($dir_info) == 2 && $dir_info[0] == 'Plugin') {
                $is_beikeshop_plugin = true;
            }

            $zip->close();
        } else {
            throw new Exception('无法打开ZIP文件或文件不存在!');
        }

        return [
            'is_beikeshop_plugin' => $is_beikeshop_plugin,
            'is_error'            => $is_error,
            'error_dir'           => $error_dir,
            'dir_info'            => $dir_info,
        ];
    }
}
