<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/systemset_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/express.php');
class AddExpressTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testExpressTest()
    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //点击系统
                ->click(admin_top['system_set'])
                //2.点击系统设置
                ->click(system_left['system_set'])
                //3.点击邮件设置
                ->click(system_set['express_set'])
                ->pause(2000)
                //点击+号
                ->click(express_set['add_btn'])
                //填写快递信息
                ->type(express_set['express_company'], express['express_company'])
                ->type(express_set['express_code'], express['express_code'])
                ->press(express_set['save_btn'])
                ->pause(5000)

                ->assertSee(express_assert['assert_ful'])
                ->pause(3000)

            ;
        });
    }

}
