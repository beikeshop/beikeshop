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
use Tests\Data\Admin\Express;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\SystemSetPage;
use Tests\DuskTestCase;

class AddExpressTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testExpressTest()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                ->pause(2000)
                //点击系统
                ->click(AdminPage::TOP['system_set'])
                //2.点击系统设置
                ->click(SystemSetPage::System_Left['system_set'])
                //3.点击快递公司
                ->click(SystemSetPage::System_Set['express_set'])
                ->pause(2000)
                //点击+号
                ->click(SystemSetPage::System_Express['add_btn'])
                //填写快递信息
                ->type(SystemSetPage::System_Express['express_company'], Express::Express['express_company'])
                ->type(SystemSetPage::System_Express['express_code'], Express::Express['express_code'])
                ->press(SystemSetPage::System_Express['save_btn'])
                ->pause(5000)

                ->assertSee(SystemSetPage::Assert['assert_ful'])
                ->pause(3000);
        });
    }
}
