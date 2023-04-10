<?php



namespace Tests\Browser;
namespace App\Http\Controllers;
namespace  App\Http\Controllers\LoginGrounp;



use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/login.php');
require_once(dirname(__FILE__) . '/../../data/login_page.php');

class LoginGrounp extends DuskTestCase
{

//    const LOGIN_DATA = [
//        'aa'
//    ];

    /**
     * A basic browser test example.
     */
    public function runScenarios(array $scenarios)
    {
        foreach ($scenarios as $scenario) {
            $this->$scenario();
        }
    }

    #1.打开浏览器
    public function openurl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->pause(2000);
        });
    }

    #输入正确email
    public function true_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->type(login['login_email'], true_login['email']);
        });
    }
    #输入错误的email
    public function false_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->type(login['login_email'], false_login['false_email']);
        });
    }
    #输入非法的email
    public function illegal_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->type(login['login_email'], false_login['illegal_email']);
        });
    }
    #输入正确的password
    public function true_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->type(login['login_pwd'], true_login['password']);
        });
    }
    #输入错误的password
    public function false_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->type(login['login_pwd'], false_login['false_password']);
        });
    }
    #点击login
    public function click_login_btn()
    {
        $this->browse(function (Browser $browser) {
            $browser->press(login['login_btn']);
        });
    }
    #登录正确断言
    public function true_assert()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertSee(true_login['assert'])
                ->pause(3000)
                ->quit();
        });
    }
    #登录错误断言
    public function false_assert()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertSee(false_login['false_assert'])
                ->pause(3000)
                ->quit();
        });
    }
    #组合测试
    #场景1---密码账号正确
    public function test_login_ful()
    {
        $this->runScenarios([
            'openurl',
            'true_email',
            'true_password',
            'click_login_btn'=> function (Browser $browser) {
                $browser->pause(1000) // 等待页面跳转
                ->assertPathIs('/account');
            },
            'true_assert' => function (Browser $browser) {
                $browser->assertSee('欢迎回来！');
            },
        ]);
    }
    #场景2---密码错误、账号正确
    public function test_pwd_error()
    {
        $this->runScenarios([
            'openurl',
            'true_email',
            'false_password',
            'click_login_btn',
            'false_assert',
        ]);
    }
    #场景3---密码正确、账号错误
    public function test_email_error()
    {
        $this->runScenarios([
            'openurl',
            'false_email',
            'true_password',
            'click_login_btn',
            'false_assert',
        ]);
    }

#场景4---密码正确、账号非法
    public function test_email_illegal()
    {
        $this->runScenarios([
            'openurl',
            'false_email',
            'illegal_email',
            'click_login_btn',
            'false_assert',
        ]);
    }
}
