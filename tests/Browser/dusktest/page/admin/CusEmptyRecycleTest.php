<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer_page.php');
class CusEmptyRecycleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法

   public function testEmptyRecycle()
   {

       $this->browse(function (Browser $browser)
       {
           $browser->visit(admin_login['login_url'])
               //1.登录
               ->type(admin_login['login_email'], admin_true_login['email'])
               ->type(admin_login['login_pwd'], admin_true_login['password'])
               ->press(admin_login['login_btn'])
               ->pause(2000)
               //点击客户
               ->click(admin_top['mg_customers'])
               //2.点击回收站
               ->click(customer_left['re_station'])
               //3.点击清空回收站
               ->press(empty_recycle['empty_btn'])
               ->pause(2000)
               ->press(empty_recycle['sure_btn'])
               ->pause(2000)
               ->assertSee(empty_recycle['assert_text'])
               ;
       });
   }
}
