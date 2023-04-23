<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');

class GoPluginsTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
        public function testGopLugins()
    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.插件市场
                ->click(admin_top['plugins_market'])
                ->pause(2000)
                //3.根据地址获取断言
                ->assertPathIs(admin_assert['plugins_assert'])
;
            ;
        });
    }
}
