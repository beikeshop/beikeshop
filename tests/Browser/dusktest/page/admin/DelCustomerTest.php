<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer_page.php');

class DelCustomerTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法

   public function testDelCustomer()
   {

       $this->browse(function (Browser $browser)
       {
           $browser->visit(admin_login['login_url'])
               //1.登录
               ->type(admin_login['login_email'], admin_true_login['email'])
               ->type(admin_login['login_pwd'], admin_true_login['password'])
               ->press(admin_login['login_btn'])
               ->pause(2000)
               //2.点击客户管理
               ->click(admin_top['mg_customers']);
               $customer_text=$browser->text(customer_list['get_assert']);
               echo $customer_text;
               $browser->press(customer_list['del_customer'])
               //确认
               ->press(customer_list['sure_btn']);
               $browser->pause(2000)
               ->assertDontSee($customer_text)
               ->pause(5000)

           ;
       });
   }
}
