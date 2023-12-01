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

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class AdminSignOutTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

    //后台退出
    public function testAdminSignOut()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //2.退出
                ->click(AdminPage::TOP['root'])
                ->pause(2000)
                ->click(AdminPage::TOP['sign_out'])
                ->pause(10000)
                ->assertSee(AdminLoginPage::Admin_Login['tltle']);
            //                ->assertSee(true_login['assert']);
        });
    }
}
