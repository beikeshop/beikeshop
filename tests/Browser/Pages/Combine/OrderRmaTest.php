<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-10-24 10:17:04
 * @modified   2023-06-06 10:17:04
 */

namespace Tests\Browser\Pages\Combine;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminOrderPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Catalog\AccountPage;
use Tests\Data\Catalog\CataLoginData;
use Tests\Data\Catalog\IndexPage;
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\RmasData;
use Tests\Data\Catalog\RmasPage;
use Tests\DuskTestCase;

//已注册客户且有地址，直接购买商品
class OrderRmaTest extends DuskTestCase
{
    public function testOrderRma()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                ->click(AdminPage::TOP['root'])
                ->pause(3000)
                ->click(AdminPage::TOP['go_catalog'])
                ->pause(2000)
                //切换到前台下单
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //前台用户登录
            //点击登录图标
            $browser->click(IndexPage::Index_Login['login_icon'])
                ->type(LoginPage::Login['login_email'], CataLoginData::True_Login['email'])
                ->type(LoginPage::Login['login_pwd'], CataLoginData::True_Login['password'])
                ->press(LoginPage::Login['login_btn'])
                ->pause(5000)
                ->click(AccountPage::Account['go_order'])
            //点击订单-详情
                ->click(AccountPage::Account['go_order'])
                ->click(AccountPage::Order['check_btn'])
            //点击售后按钮
                ->click(AccountPage::Order['rma-btn'])
            //填写售后信息
                ->type(RmasPage::Rmas['Remark'], RmasData::Rmas['Remark_text'])
                ->press(RmasPage::Rmas['Submit'])
            //进入后台,查看售后订单
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[0]);
            //点击订单管理按钮
            $browser->click(AdminPage::TOP['mg_order'])
                ->press(AdminOrderPage::Child['mg_sale_after'])
                ->pause(3000)
            //点击查看按钮-修改状态为已完成
                ->click(AdminOrderPage::Rams['Check_btn'])
                ->click(AdminOrderPage::Rams['Pull_btn'])
                ->click(AdminOrderPage::Rams['Completed'])
                ->press(AdminOrderPage::Rams['Update_btn'])
                ->pause(10000)
            //切换到前台
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->pause(3000)
                ->click(RmasPage::Rmas['Checkout-btn'])
            //刷新页面
                ->assertSee(RmasData::Rmas['Asser_text']);

        });
    }
}
