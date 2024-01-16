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

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class GoCopyrightAndServiceTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testGoCopyrightAndService()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //2.点击版权与服务图标
                ->click(AdminPage::TOP['buy_copyright'])
                ->pause(2000)
                //3.切换到第二个窗口并获取断言
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            $browser->assertSee(AdminPage::Assert['vip_assert']);
        });
    }
}
