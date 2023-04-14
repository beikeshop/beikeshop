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
class CartCheckoutTest extends DuskTestCase
{
    public function testCartCheckout()
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
                //4.点击要加入购物车的商品
                ->press(index['product_img'])
                ->pause(2000)
                //5.点击加入购物车
                ->press(product['add_cart'])
                ->pause(3000)
                //6.点击购物车按钮
                ->clickLink(index_cart['cart_icon'])
                ->pause(3000)
                //7.点击结账按钮
                ->press(index_cart['cart_Checkout'])
                ->pause(5000)
                //8.点击确认按钮
                ->press(checkout['submit'])
                ->pause(5000)
                //9.断言
                ->assertSee(checkout['assert'])

            ;
        });
    }
}
