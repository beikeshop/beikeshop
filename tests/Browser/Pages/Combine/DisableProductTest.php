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

namespace Tests\Browser\Pages\Combine;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\CreProductPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\ProductPage;
use Tests\DuskTestCase;

class DisableProductTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */

    //启用商品

    public function testDisableProduct()
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
            //编辑商品
            $browser->press(ProductPage::Product_Top['edit_product'])
                ->scrollIntoView(CreProductPage::Product_Top['Enable'])
                ->pause(2000)
            //启用商品
                ->click(CreProductPage::Product_Top['Disable'])
            //点击保存
                ->press(CreProductPage::Product_Top['save_btn'])
                ->pause(3000)
            //点击商品，跳转前台
                ->clickLink($product1_text)
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //断言是否有下架提示
            $browser->assertVisible(CreProductPage::Product_Assert['Disable_text'])
                ->pause(3000);
        });
    }
}
