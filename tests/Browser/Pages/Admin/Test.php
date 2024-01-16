<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class Test extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testHomePage()
    {
        //        \Tests\Data\Admin\AdminPage::TOP['login_url'];

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::ADMINLOGIN['login_url'])
                ->type(AdminLoginPage::ADMINLOGIN['login_email'], LoginData::TURE['email'])
                ->type(AdminLoginPage::ADMINLOGIN['login_pwd'], LoginData::TURE['password'])
                ->press(AdminLoginPage::ADMINLOGIN['login_btn'])
                ->pause(7000);

        });
    }
}
