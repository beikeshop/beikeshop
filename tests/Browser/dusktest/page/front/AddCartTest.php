<?php
namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/product_1.php');
require_once(dirname(__FILE__) . '/../../data/catalog/index_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/checkout_page.php');

//已注册客户且有地址，直接购买商品
class AddCartTest extends DuskTestCase
{
    public function testAddCart()
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
//              ->mouseover(index['product_img'])
                ->pause(2000)
                //2.点击要加入购物车的商品
                ->press(index['product_img'])
                ->pause(2000);

             //3.获取当前产品标题
                $product_description = $browser->text(product['product1_name']);
                $ProductUrl = $browser->driver->getCurrentURL();
            //4.点击收藏按钮
               $browser->visit($ProductUrl)
            //5.点击加入购物车

                ->press(product['add_cart'])
                ->pause(3000)
                //6.点击购物车按钮
                ->clickLink(index_cart['cart_icon'])
                ->pause(10000);
                //6.断言购物车内商品是否与先前商品相同
               $browser->assertSeeIn(index_cart['product_text'],$product_description)
                  ;
        });
    }
}
