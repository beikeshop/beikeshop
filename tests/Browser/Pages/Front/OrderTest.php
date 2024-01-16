<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-06-06 17:17:04
 * @modified   2023-06-06 17:17:04
 */

namespace Tests\Browser\Pages\Front;

use Laravel\Dusk\Browser;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\CheckoutPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

//已注册客户且有地址，直接购买商品
class OrderTest extends DuskTestCase
{
    public function testOrder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                //当前网址断言
                ->assertPathIs(AccountPage::Account['url'])
                //打印查看当前网址
                //                $html = $browser->driver->getCurrentURL();
                //                print_r($html)
                //                ->driver->getCurrentURL()
                //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                //3.向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
                //              ->mouseover(index['product_img'])
                ->pause(2000)
                //点击要购买的商品
                ->press(IndexPage::Index['product_img'])
                //4.点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(5000)
                //5.点击确认按钮
                ->press(CheckoutPage::Checkout['submit'])
                ->pause(5000)
                //6.断言
                ->assertSee(CheckoutPage::Checkout['assert']);
        });
    }
}
