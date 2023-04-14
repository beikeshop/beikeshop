<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/login.php');
require_once(dirname(__FILE__) . '/../../data/login_page.php');
require_once(dirname(__FILE__) . '/../../data/account_page.php');

class SignOutTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法
    public function testSignOut()

    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(login['login_url'])
                //1.登录
                ->type(login['login_email'], true_login['email'])
                ->type(login['login_pwd'], true_login['password'])
                ->press(login['login_btn'])
                ->pause(2000)
                //2.退出
                ->clickLink(account['SignOut'])
                ->pause(3000)
                ->assertSee(false_login['false_assert']);
//                ->assertSee(true_login['assert']);
        });
    }
}
