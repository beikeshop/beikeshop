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

//已注册客户且有地址，在下单时更换支付方式购买
class ChangePayMethodTest extends DuskTestCase
{
    public function testChangePayMethod()
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
            //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
            //3.向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
//点击要购买的商品
                ->press(IndexPage::Index['product_img'])
            //4.点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(5000)
//点击第二种支付方式
                ->elements(CheckoutPage::Checkout['method_pay'])[1]->click();
            $browser->pause(5000)
            //5.点击确认按钮
                ->press(CheckoutPage::Checkout['submit'])
                ->pause(5000)
             //6.断言
                ->assertSee(CheckoutPage::Checkout['assert']);
        });
    }
}
