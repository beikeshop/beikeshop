<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

require_once(dirname(__FILE__) . '/../../data/admin/login.php');
require_once(dirname(__FILE__) . '/../../data/admin/login_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/admin_page.php');
require_once(dirname(__FILE__) . '/../../data/catalog/index_page.php');
class GoCatalogTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */



//场景1 email不合法
    public function testGoCatalog()
    {

        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
                //1.登录
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //2.去往前台
                ->clicklink(admin_top['root'])
                ->pause(3000)
                ->clickLink(admin_top['go_catalog'])
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
                $browser->assertPathIs(index['login_url'])
                    ;
                });
    }
}
