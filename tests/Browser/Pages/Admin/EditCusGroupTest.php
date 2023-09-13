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
use Tests\Data\Admin\CusGrounp;
use Tests\Data\Admin\CustomerPage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class EditCusGroupTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testEditCusGroup()
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
                ->click(CustomerPage::Left['customer_group'])
                //5.点击编辑客户组
                ->press(CustomerPage::Customer_Group['edit_cus_group'])
                //4.填写客户组信息
                ->type(CustomerPage::Create_Group['ch_group_name'], CusGrounp::Group_Info['ch_group_name'])
                ->type(CustomerPage::Create_Group['en_group_name'], CusGrounp::Group_Info['en_group_name'])
                ->type(CustomerPage::Create_Group['ch_description'], CusGrounp::Group_Info['ch_description'])
                ->type(CustomerPage::Create_Group['en_description'], CusGrounp::Group_Info['en_description'])
                ->type(CustomerPage::Create_Group['discount'], CusGrounp::Group_Info['discount'])
                //5.点击保存
                ->press(CustomerPage::Create_Group['save_btn'])
                ->pause(5000)
                ->assertSee(CusGrounp::Group_Info['ch_group_name']);
        });
    }
}
