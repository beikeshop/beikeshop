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
use Tests\Data\Admin\CustomerPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class DelCusGroupTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testDelCusGroup()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //2.点击客户管理
                ->click(AdminPage::TOP['mg_customers'])
                ->pause(3000)
                //4.点击客户组
                ->click(CustomerPage::Left['customer_group']);

            $cus_group_text = $browser->text(CustomerPage::Customer_Group['get_assert']);
            echo $cus_group_text;
            //5.点击删除按钮
            $browser->press(CustomerPage::Customer_Group['del_cus_group'])
                ->pause(2000)
                ->assertSee($cus_group_text);
        });
    }
}
