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

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarketingService
{
    public static function getList($filters = [])
    {
        $url = config('beike.api_url') . '/api/plugins';
        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }
        return Http::withOptions(['verify' => false])->get($url)->json();
    }

    public static function getPlugin($code)
    {
        $url = config('beike.api_url') . '/api/plugins/' . $code;
        $plugin = Http::withOptions(['verify' => false])->get($url)->json();
        if (empty($plugin)) {
            throw new NotFoundHttpException('该插件不存在或已下架');
        }
        return $plugin;
    }
}
