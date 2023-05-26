<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\CustomerPage;

class CustomerRecycleTest extends DuskTestCase
{
   /**
    * A basic browser test example.
    * @return void
    */
   public function testCustomerRecycle()
   {

       $this->browse(function (Browser $browser) {
           $browser->visit(AdminLoginPage::Admin_Login['login_url'])
               //1.登录
               ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
               ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
               ->press(AdminLoginPage::Admin_Login['login_btn'])
               ->pause(2000)
               ->click(AdminPage::TOP['mg_customers'])
               //2.点击回收站
               ->click(CustomerPage::Left['re_station']);
               $customer_text = $browser->text(CustomerPage::Empty_Recycle['customer_text']);
               echo $customer_text;
               //3.点击恢复按钮
               $browser->press(CustomerPage::Empty_Recycle['recycle_btn'])
               ->pause(2000)
               //4.点击客户列表
               ->click(CustomerPage::Left['customer_list'])
                   //验证客户信息是否存在于页面
               ->assertSee($customer_text);
       });
   }
}
