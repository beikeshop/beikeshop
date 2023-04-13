<?php


namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/register.php');
require_once(dirname(__FILE__) . '/../../data/login_page.php');

class RegisterTest extends DuskTestCase
{

    /**
     * A basic browser test example.
     */
    //场景1  使用已注册过的邮箱注册
    public function testUsedEmail()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], false_register['exist_email'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景2  前后密码输入不一致
    public function testDiffPwd()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], true_register['email'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], false_register['false_password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景3  邮箱格式不合法
    public function testIllegalEmail()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], false_register['illegal_email'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景4  邮箱为空
    public function testNoEmail()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景5  密码为空
    public function testNoPwd()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], true_register['email'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景6  第二次密码为空
    public function testNoRepwd()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], true_register['email'])
                ->type(register['register_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->assertSee(false_register['false_assert']);
        });
    }
    //场景7  第二次密码为空
    public function testRegisterFul()

    {
        $this->browse(function (Browser $browser) {
            $browser->visit(login['login_url'])
                ->type(register['register_email'], true_register['email'])
                ->type(register['register_pwd'], true_register['password'])
                ->type(register['register_re_pwd'], true_register['password'])
                ->press(register['register_btn'])
                ->pause(3000)
                ->assertSee(true_register['assert']);
        });
    }

}
