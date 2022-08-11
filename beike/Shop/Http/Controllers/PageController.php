<?php
/**
 * PageController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-11 18:13:06
 * @modified   2022-08-11 18:13:06
 */

namespace Beike\Shop\Http\Controllers;


use Beike\Models\Page;
use Beike\Shop\Http\Resources\PageDetail;

class PageController extends Controller
{
    public function show(Page $page)
    {
        $page->load('description');
        $data = [
            'page' => (new PageDetail($page))->jsonSerialize()
        ];
        dd($data);
    }
}
