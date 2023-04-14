<?php
namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/login.php');
require_once(dirname(__FILE__) . '/../../data/login_page.php');
require_once(dirname(__FILE__) . '/../../data/account_page.php');
require_once(dirname(__FILE__) . '/../../data/product_1.php');
require_once(dirname(__FILE__) . '/../../data/index_page.php');
require_once(dirname(__FILE__) . '/../../data/checkout_page.php');

//已注册客户且有地址，直接购买商品
class RemoveWishlistTest extends DuskTestCase
{
    public function testReWishlist()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                //1.用户登录
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(2000)
                //2.点击home跳转到首页
                ->clickLink(account['go_index'])
                //3.向下滑动页面直到找到元素
                ->scrollIntoView(index['product_img'])
                ->pause(2000)
                ->press(index['product_img'])
                ->pause(2000);
                //4.保存当前网址
                $ProductUrl = $browser->driver->getCurrentURL();
                //5.点击收藏按钮
                $browser->visit($ProductUrl)
                ->press(product['Wishlist_icon'])
                ->pause(3000)
                //6.点击顶部收藏认按钮
                ->clickLink(index_top['wishlist_btn'])
//                ->pause(1000)
                //7.点击移除按钮
                ->press(Wishlist['remove_Wishlist'])
                ->pause(1000)
                //8.断言
//                ->assertUrlIs($ProductUrl,$browser->driver->getCurrentURL())
            ;
        });
    }
}
