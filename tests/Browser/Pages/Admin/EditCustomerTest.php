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
use Tests\Data\Admin\CustomerData;
use Tests\Data\Admin\CustomerPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class EditCustomerTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testEditCustomer()
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
                //3.点击编辑按钮
                ->press(CustomerPage::Group_list['edit_customer'])
                //4.填写客户信息
                ->type(CustomerPage::Alter['name'], CustomerData::Customer_Info_Alter['name'])
                ->type(CustomerPage::Alter['email'], CustomerData::Customer_Info_Alter['email'])
                ->type(CustomerPage::Alter['pwd'], CustomerData::Customer_Info_Alter['pwd'])
                //5.点击保存
                ->press(CustomerPage::Alter['save_btn'])
                ->pause(5000)
                ->assertSee(CustomerData::Customer_Info_Alter['email']);
        });
    }
}
