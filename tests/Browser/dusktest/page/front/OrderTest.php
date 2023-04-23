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
class OrderTest extends DuskTestCase
{
    public function testOrder()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                //1.用户登录
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(5000)
                //当前网址断言
                ->assertPathIs(account['url'])
//打印查看当前网址
//                $html = $browser->driver->getCurrentURL();
//                print_r($html)
//                ->driver->getCurrentURL()
            //2.点击home跳转到首页
               ->click(account['go_index'])
            //3.向下滑动页面直到找到元素
              ->scrollIntoView(index['product_img'])
//              ->mouseover(index['product_img'])
              ->pause(2000)
//点击要购买的商品
              ->press(index['product_img'])
            //4.点击购买按钮
              ->press(product['product_1'])
              ->pause(5000)
            //5.点击确认按钮
            ->press(checkout['submit'])
            ->pause(5000)
             //6.断言
            ->assertSee(checkout['assert']);
        });
    }
}
