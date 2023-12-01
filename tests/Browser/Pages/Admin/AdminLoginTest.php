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
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class AdminLoginTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

    //场景1 email不合法
    public function testEmailIllegal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::False_Data['illegal_email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->assertSee(LoginData::False_Data['illegal_assert']);
        });
    }

    //场景2 email不存在
    public function testEmailFalse()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::False_Data['false_email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->assertSee(LoginData::False_Data['false_assert']);
        });
    }

    //场景3 密码错误
    public function testPwdFalse()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::False_Data['false_password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->assertSee(LoginData::False_Data['false_assert']);
        });
    }

    //场景4 只输入email
    public function testOnlyEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->assertSee(LoginData::False_Data['no_pwd']);
        });
    }

    //场景5 只输入密码
    public function testOnlyPwd()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['email'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->assertSee(LoginData::False_Data['no_email']);
        });
    }

    //场景6 成功登录
    public function testLoginFul()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                ->assertSee(LoginData::Ture_Data['assert']);
        });
    }
}
