<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');

class LoginTest extends DuskTestCase
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
            $browser->visit(login['login_url'])
                ->type(login['login_email'], false_login['illegal_email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->assertSee(false_login['illegal_assert']);
        });
    }
    //场景2 email不存在
    public function testEmailFalse()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_email'], false_login['false_email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->assertSee(false_login['false_assert']);
        });
    }
//场景3 密码错误
    public function testPwdFalse()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], false_login['false_password'])
                ->press(login['login_btn'])
                ->assertSee(false_login['false_assert']);
        });
    }
    //场景4 只输入账号
    public function testOnlyEmail()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_email'], true_login['email'])
                ->press(login['login_btn'])
                ->assertSee(false_login['false_assert']);
        });
    }
    //场景5 只输入密码
    public function testOnlyPwd()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_pwd'], true_login['email'])
                ->press(login['login_btn'])
                ->assertSee(false_login['false_assert']);
        });
    }
    //场景6 成功登录
    public function testLoginFul()

    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(2000)
                ->assertSee(true_login['assert']);
        });
    }
}
