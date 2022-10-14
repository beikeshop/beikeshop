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

use ZanySoft\Zip\Zip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\PendingRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarketingService
{
    private PendingRequest $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'developer-token' => system_setting('base.developer_token'),
        ]);
    }

    public static function getInstance()
    {
        return new self;
    }

    public function getList($filters = [])
    {
        $url = config('beike.api_url') . '/api/plugins';
        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }
        return $this->httpClient->get($url)->json();
    }

    public function getPlugin($pluginCode)
    {
        $url = config('beike.api_url') . '/api/plugins/' . $pluginCode;
        $plugin = $this->httpClient->get($url)->json();
        if (empty($plugin)) {
            throw new NotFoundHttpException('该插件不存在或已下架');
        }
        return $plugin;
    }

    public function download($pluginCode)
    {
        $datetime = date('Y-m-d');
        $url = config('beike.api_url') . "/api/plugins/{$pluginCode}/download";

        $content = $this->httpClient->get($url)->body();

        $pluginPath = "plugins/{$pluginCode}-{$datetime}.zip";
        Storage::disk('local')->put($pluginPath, $content);

        $pluginZip = storage_path('app/' . $pluginPath);
        $zipFile = Zip::open($pluginZip);
        $zipFile->extract(base_path('plugins'));
    }

}
