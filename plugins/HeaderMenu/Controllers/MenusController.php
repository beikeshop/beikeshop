<?php
/**
 * MenusController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 10:00:25
 * @modified   2022-07-21 10:00:25
 */

namespace Plugin\HeaderMenu\Controllers;

use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Controllers\Controller;

class MenusController extends Controller
{
    public function latestProducts()
    {
        $products = ProductRepo::getBuilder()->orderByDesc('updated_at')->paginate(40);
        return view("HeaderMenu::latest_products", ['products' => $products]);
    }
}
