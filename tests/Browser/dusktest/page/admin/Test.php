<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/cus_grounp.php');
require_once(dirname(__FILE__) . '/../../data/admin/customer_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/systemset_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/order_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/express.php');

class Test extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testAddCusGroup()
    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                //登录后台
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //去往前台
                ->pause(3000)
                ->click(admin_top['mg_order'])
                //点击查看按钮
                ->press(order_right['view_btn'])
                //点击状态栏下拉按钮
                ->pause(2000)
                ->press(order_details['pull_btn'])
                //修改状态为已支付
                ->pause(2000)

                ->click(order_details['Shipped'])
                ->press(order_details['express_btn'])
                //修改状态为已支付
                ->pause(5000)
                ->waitFor(order_details['express_1'])
                ->click(order_details['express_1'])
                ->type(order_details['order_number'], express['password'])
                ->press(order_details['submit'])
                ->pause(3000)
            ;



            ;
        });
    }
}
