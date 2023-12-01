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

namespace Tests\Browser\Pages\Combine;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\SystemSetPage;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

//禁止游客结账
class CloseVisiterCheckoutTest extends DuskTestCase
{
    public function testCloseVisiterCheckout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //点击系统设置
                ->click(AdminPage::TOP['system_set'])
                //点击结账设置
                ->click(SystemSetPage::System_Set['pay_set'])
                ->pause(2000)
                //点击结游客结账 禁用
                ->press(SystemSetPage::System_Set['close_visitor_checkout'])
                //点击保存
                ->press(SystemSetPage::Common['save_btn'])
                ->pause(2000)
        //去往前台验证
                ->click(AdminPage::TOP['root'])
                ->pause(3000)
                ->click(AdminPage::TOP['go_catalog'])
                ->pause(2000)
                //切换到前台下单
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //前台用户登录
            $browser->pause(2000)
            //向下滑动页面直到找到商品
                ->scrollIntoView(IndexPage::Index['product_img'])
                ->pause(2000)
            //点击要购买的商品
                ->press(IndexPage::Index['product_img'])
            //点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(5000)
        //断言：出现登录窗体则通过
                ->assertVisible(LoginPage::Iframe['iframe_name']);

        });
    }
}
