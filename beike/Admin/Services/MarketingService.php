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
use Beike\Facades\BeikeHttp\Facade\Http;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ZanySoft\Zip\Zip;
use ZipArchive;

class MarketingService
{
    public static function getInstance(): MarketingService
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

        request()->merge(['version' => config('beike.version')]);

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

        request()->merge(['domain' => $domain]);

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

        request()->merge(['version' => config('beike.version')]);

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
    public function download($pluginCode)
    {
        $datetime         = date('Y-m-d');
        $apiEndPoint      = "/v1/plugins/{$pluginCode}/download";

        $content = Http::sendGet($apiEndPoint, ['timeout' => null], 'body');
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
                        if (count($info2) > 1) {
                            $dir   = $info2[1];
                            $plugin_dir = base_path('plugins');
                            $zipFile->extract($plugin_dir);

                            $error_dir = base_path('plugins/' . $info['error_dir']);
                            $ok_dir    = base_path('plugins/' . $dir);

                            $result = @rename($error_dir, $ok_dir);
//                            if (! $result) {
//                                throw new Exception('重命名插件文件夹失败');
//                            }
                        }
                    } else {
                        //散开的文件
                        if (count($info2) > 1) {
                            $dir        = $info2[1];
                            $plugin_dir = base_path('plugins/' . $dir);

                            if (! is_dir($plugin_dir)) {
                                (new Filesystem)->makeDirectory($plugin_dir);
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
        $apiEndPoint = '/v1/website/get_domain';
        request()->merge(['token' => $token]);

        return Http::sendGet($apiEndPoint);
    }

    // getToken
    public function getToken($domain)
    {
        $apiEndPoint = '/v1/website/get_token';
        request()->merge(['domain' => $domain]);

        return Http::sendGet($apiEndPoint);
    }

    public function getLicensedPro($domain,$from)
    {
        $apiEndPoint = '/v1/licensed_pro';
        request()->merge(['domain' => $domain, 'from' => $from]);

        return Http::sendGet($apiEndPoint);
    }

    // getToken
    public function checkToken($domain, $token)
    {
        $apiEndPoint = '/v1/website/check_token';
        request()->merge(['domain' => $domain , 'token' => $token]);

        return Http::sendGet($apiEndPoint);
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
    public function getPluginInfo($zip_file): array
    {
        $dir_info            = [];
        $configInfo          = [];
        $error_dir           = '';
        $is_error            = false;
        $is_beikeshop_plugin = false;

        $zip                 = new ZipArchive;

        if ($zip->open($zip_file) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];
                $fileExtension = pathinfo($name);
                if ($fileExtension['basename'] =='config.json') {
                    $configInfo = $fileExtension;
                    break;
                }
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];
                $fileExtension = pathinfo($name,PATHINFO_EXTENSION);
                if ($fileExtension == 'php') {
                    $content  = $zip->getFromIndex($i);
                     preg_match('/namespace\s+([^\s;]+);/', $content, $matches);
                    if ($matches) {
                        $dir_info = explode('\\', $matches[1]);
                        break;
                    }
                }
            }

            if (isset($configInfo['dirname']))
            {
                $dirName = $configInfo['dirname'];
                if ($dirName == '.') {
                    $is_error = true;
                } else {
                    if (count($dir_info) > 1) {
                        $_pluginDir = $dir_info[1];
                        if (rtrim($dirName, '/') != $_pluginDir) {
                            $is_error  = true;
                            $error_dir = rtrim($dirName, '/');
                        }
                    } else {
                        $is_beikeshop_plugin = true;
                    }
                }

            }

            if (count($dir_info) > 1 && $dir_info[0] == 'Plugin') {
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

    public function checkPluginVersion($pluginCodes)
    {
        $apiEndPoint = "/v1/plugins/version";
        request()->merge(['fields' => $pluginCodes]);
        return Http::sendGet($apiEndPoint);
    }
}
