<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');

class AdminLoginTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法
    public function testEmailIllegal()

    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_email'], admin_false_login['illegal_email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->assertSee(admin_false_login['illegal_assert']);
        });
    }
    //场景2 email不存在
    public function testEmailFalse()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_email'], admin_false_login['false_email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->assertSee(admin_false_login['false_assert']);
        });
    }
//场景3 密码错误
    public function testPwdFalse()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_false_login['false_password'])
                ->press(admin_login['login_btn'])
                ->assertSee(admin_false_login['false_assert']);
        });
    }
    //场景4 只输入email
    public function testOnlyEmail()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->press(admin_login['login_btn'])
                ->assertSee(admin_false_login['no_pwd']);
        });
    }
    //场景5 只输入密码
    public function testOnlyPwd()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_pwd'], admin_true_login['email'])
                ->press(admin_login['login_btn'])
                ->assertSee(admin_false_login['no_email']);
        });
    }
    //场景6 成功登录
    public function testLoginFul()

    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                ->assertSee(admin_true_login['assert']);
        });
    }
}
