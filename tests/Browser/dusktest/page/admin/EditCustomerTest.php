<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once dirname(__FILE__) . '/../../data/admin/login.php';
require_once dirname(__FILE__) . '/../../data/admin/login_page.php';
require_once dirname(__FILE__) . '/../../data/admin/admin_page.php';
require_once dirname(__FILE__) . '/../../data/admin/customer.php';
require_once dirname(__FILE__) . '/../../data/admin/customer_page.php';
class EditCustomerTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testEditCustomer()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.点击客户管理
                ->click(admin_top['mg_customers'])
                //3.点击编辑按钮
                ->press(customer_list['edit_customer'])
                //4.填写客户信息
                ->type(alter_customer['name'], customer_info_alter['name'])
                ->type(alter_customer['email'], customer_info_alter['email'])
                ->type(alter_customer['pwd'], customer_info_alter['pwd'])
                //5.点击保存
                ->press(alter_customer['save_btn'])
                ->pause(5000)
                ->assertSee(customer_info_alter['email']);
                });
    }
}
