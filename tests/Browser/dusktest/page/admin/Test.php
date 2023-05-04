<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\By;

require_once(dirname(__FILE__) . '/../../data/catalog/login.php');
require_once(dirname(__FILE__) . '/../../data/catalog/login_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/account_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/product_1.php');
require_once(dirname(__FILE__) . '/../../data/catalog/index_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/checkout_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/order_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/order_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/express.php');
require_once(dirname(__FILE__) . '/../../data/admin/product_page.php');


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
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                ->click(admin_top['mg_order'])
                ->pause(2000)
                ->click(order_child['mg_order'])
                ->pause(2000)
                ->click(order_child['mg_sale_after'])
                ->pause(2000)
                ->click(order_child['ca_sale_after'])
                ->pause(2000)


                ;
        });
    }
}
