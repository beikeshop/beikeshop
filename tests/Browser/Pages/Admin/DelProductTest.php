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
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\ProductPage;
use Tests\DuskTestCase;

class DelProductTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

    //场景1 email不合法

    public function testDelProduct()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                ->click(AdminPage::TOP['mg_product']);
            $product1_text = $browser->text(ProductPage::Product_Top['get_name']);
            echo $product1_text;
            //2.删除按钮
            $browser->press(ProductPage::Product_Top['del_product'])
            //确认
                ->press(ProductPage::Product_Top['sure_btn']);
            $browser->pause(2000)
                ->assertDontSee($product1_text)
                ->pause(5000);
        });
    }
}
