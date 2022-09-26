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
use Beike\Admin\Services\MarketingService;

class MarketingController
{
    public function index(Request $request)
    {
        $plugins = MarketingService::getList();
        $data = [
            'plugins' => $plugins,
        ];
        return view('admin::pages.marketing.index', $data);
    }
}
