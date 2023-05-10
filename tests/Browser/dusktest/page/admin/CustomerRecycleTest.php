<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once dirname(__FILE__) . '/../../data/admin/login.php';
require_once dirname(__FILE__) . '/../../data/admin/login_page.php';
require_once dirname(__FILE__) . '/../../data/admin/admin_page.php';
require_once dirname(__FILE__) . '/../../data/admin/customer_page.php';
class CustomerRecycleTest extends DuskTestCase
{
   /**
    * A basic browser test example.
    * @return void
    */
   public function testCustomerRecycle()
   {

       $this->browse(function (Browser $browser) {
           $browser->visit(admin_login['login_url'])
               //1.登录
               ->type(admin_login['login_email'], admin_true_login['email'])
               ->type(admin_login['login_pwd'], admin_true_login['password'])
               ->press(admin_login['login_btn'])
               ->pause(2000)
               ->click(admin_top['mg_customers'])
               //2.点击回收站
               ->click(customer_left['re_station']);
               $customer_text = $browser->text(empty_recycle['customer_text']);
               echo $customer_text;
               //3.点击恢复按钮
               $browser->press(empty_recycle['recycle_btn'])
               ->pause(2000)
               //4.点击客户列表
               ->click(customer_left['customer_list'])
                   //验证客户信息是否存在于页面
               ->assertSee($customer_text);
       });
   }
}
