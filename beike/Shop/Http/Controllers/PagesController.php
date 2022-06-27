<?php
/**
 * PagesController.php
 *
 * @copyright  2017 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2017-08-28 19:55
 * @modified   2017-08-28 19:55
 */

namespace Beike\Shop\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function show($urlKey, Request $request)
    {
        $data = [];
        return view("pages/{$urlKey}", $data);
    }
}
