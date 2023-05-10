<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once dirname(__FILE__) . '/../../data/admin/login.php';
require_once dirname(__FILE__) . '/../../data/admin/login_page.php';
require_once dirname(__FILE__) . '/../../data/admin/admin_page.php';

class LanguageSwitchTest extends DuskTestCase
{
        /**
         * A basic browser test example.
         * @return void
         */
        public function testLanguageSwitch()
        {

        $this->browse(function (Browser $browser) {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.点击切换语言
                ->click(admin_top['sw_language'])
                //3.切换为英文
                ->click(admin_top['en_language'])
                ->pause(4000)
                ->assertSee(admin_assert['en_assert'])
                //切换回中文
                ->click(admin_top['sw_language'])
                ->click(admin_top['ch_language'])
                ->assertSee(admin_assert['ch_assert']);
        });
    }
}
