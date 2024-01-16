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
use Tests\Data\Catalog\LoginPage;
use Tests\Data\Catalog\RegisterData;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    //场景1  使用已注册过的邮箱注册
    public function testUsedEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::False_Register['exist_email'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景2  前后密码输入不一致
    public function testDiffPwd()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::True_Register['email'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::False_Register['false_password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景3  邮箱格式不合法
    public function testIllegalEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::False_Register['illegal_email'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景4  邮箱为空
    public function testNoEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景5  密码为空
    public function testNoPwd()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::True_Register['email'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景6  第二次密码为空
    public function testNoRepwd()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::True_Register['email'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->assertSee(RegisterData::False_Register['false_assert']);
        });
    }

    //场景7  第二次密码为空
    public function testRegisterFul()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(LoginPage::Login['login_url'])
                ->type(LoginPage::Register['register_email'], RegisterData::True_Register['email'])
                ->type(LoginPage::Register['register_pwd'], RegisterData::True_Register['password'])
                ->type(LoginPage::Register['register_re_pwd'], RegisterData::True_Register['password'])
                ->press(LoginPage::Register['register_btn'])
                ->pause(6000)
                ->assertSee(RegisterData::True_Register['assert']);
        });
    }
}
