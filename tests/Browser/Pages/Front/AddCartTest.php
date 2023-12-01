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
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

//已注册客户且有地址，直接购买商品
class AddCartTest extends DuskTestCase
{
    public function testAddCart()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                //3.向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
//              ->mouseover(index['product_img'])
                ->pause(2000)
                //2.点击要加入购物车的商品
                ->press(IndexPage::Index['product_img'])
                ->pause(2000);

            //3.获取当前产品标题
            $product_description = $browser->text(ProductOne::Product['product1_name']);
            $ProductUrl          = $browser->driver->getCurrentURL();
            //4.点击收藏按钮
            $browser->visit($ProductUrl)
            //5.点击加入购物车
                ->press(ProductOne::Product['add_cart'])
                ->pause(3000)
             //6.点击购物车按钮
                ->click(IndexPage::Index_Cart['cart_icon'])
                ->pause(10000);
            //6.断言购物车内商品是否与先前商品相同
            $browser->assertSeeIn(IndexPage::Index_Cart['product_text'], $product_description);
        });
    }
}
