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
class RemoveWishlistTest extends DuskTestCase
{
    public function testReWishlist()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                //1.用户登录
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(3000)
                //2.点击home跳转到首页
                ->click(AccountPage::Account['go_index'])
                ->pause(2000)
                //3.向下滑动页面直到找到元素
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
                ->press(IndexPage::Index['product_img'])
                ->pause(2000);
            //4.保存当前网址
            $ProductUrl = $browser->driver->getCurrentURL();
            //5.点击收藏按钮
            $browser->visit($ProductUrl)
                ->press(ProductOne::Product['Wishlist_icon'])
                ->pause(3000)
            //6.点击顶部收藏认按钮
                ->click(IndexPage::Index_Top['wishlist_btn'])
//                ->pause(1000)
            //7.点击移除按钮
                ->press(AccountPage::Wishlist['remove_Wishlist'])
                ->pause(3000)
            //8.断言
                ->assertVisible(AccountPage::Wishlist['no_data']); //no_data
        });
    }
}
