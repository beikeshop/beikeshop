<?php

namespace Tests\Browser;
namespace App\Http\Controllers;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/login.php');
require_once(dirname(__FILE__) . '/../../data/login_page.php');

class LoginTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */

//场景2 email不合法
    public function testEmailIllegal(): void

    {
        parent::setUp();
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->pause(2000)
                ->type(login['login_email'], false_login['illegal_email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->waitForReload()  //等待页面重载
                ->assertSee(false_login['illegal_assert'])
                ->pause(3000)
                ->quit();
        });
    }
    //场景3 email不存在
    public function testEmaiFalse(): void

    {
        parent::setUp();
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->pause(2000)
                ->type(login['login_email'], false_login['false_email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->waitForReload()  //等待页面重载
                ->assertSee(false_login['false_assert'])
                ->pause(3000)
                ->quit();
        });
    }
//场景4 密码错误
    public function testPwdFalse(): void

    {
        parent::setUp();
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->pause(2000)
                ->type(login['login_email'], false_login['false_email'])
                ->type(login['login_pwd'], false_login['false_password'])
                ->press(login['login_btn'])
                ->waitForReload()  //等待页面重载
                ->assertSee(false_login['false_assert'])
                ->pause(3000)
                ->quit();
        });

    }
    //场景1 成功登录
    public function testLoginFul(): void

    {
        parent::setUp();    // 重置测试环境和状态
        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                ->pause(2000)
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->waitForReload()  //等待页面重载
                ->assertSee(true_login['assert'])
                ->pause(3000)
                ->quit();
        });
    }

}
