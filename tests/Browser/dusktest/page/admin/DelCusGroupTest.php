<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once dirname(__FILE__) . '/../../data/admin/login.php';
require_once dirname(__FILE__) . '/../../data/admin/login_page.php';
require_once dirname(__FILE__) . '/../../data/admin/admin_page.php';
require_once dirname(__FILE__) . '/../../data/admin/cus_grounp.php';
require_once dirname(__FILE__) . '/../../data/admin/customer_page.php';
class DelCusGroupTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testDelCusGroup()
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
                ->pause(3000)
                //4.点击客户组
                ->click(customer_left['customer_group']);

                $cus_group_text = $browser->text(customer_group['get_assert']);
                echo $cus_group_text;
                //5.点击删除按钮
                $browser->press(customer_group['del_cus_group'])
                ->pause(2000)
                ->assertSee($cus_group_text);
        });
    }
}
