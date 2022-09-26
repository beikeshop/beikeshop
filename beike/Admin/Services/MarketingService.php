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

class MarketingService
{
    public static function getList()
    {
        $url = config('beike.api_url') . '/api/plugins';
        return Http::get($url)->json();
    }
}
