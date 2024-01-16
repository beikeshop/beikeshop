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
use Tests\Data\Admin\CreProduct;
use Tests\Data\Admin\CreProductPage;
use Tests\Data\Admin\LoginData;
use Tests\Data\Admin\ProductPage;
use Tests\Data\Catalog\ProductOne;
use Tests\DuskTestCase;

////库存不足时下单
class UnderstockOrderTest extends DuskTestCase
{
    public function testUnderstockOrder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //1.登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
            //修改商品库存为5
                ->click(AdminPage::TOP['mg_product']);
            //获取商品名
            $product1_text = $browser->text(ProductPage::Product_Top['get_name']);
            echo $product1_text;
            //点击编辑商品
            $browser->press(ProductPage::Product_Top['edit_product'])
                ->scrollIntoView(CreProductPage::Product_Top['Enable'])
                ->pause(2000)
            //启用商品
                ->click(CreProductPage::Product_Top['Enable'])
            //修改商品库存为5
                ->type(CreProductPage::Product_Top['quantity'], CreProduct::Alter['low_quantity'])
            //5.点击保存
                ->press(CreProductPage::Product_Top['save_btn'])
                ->pause(3000)

            //去往前台下单
                ->clickLink($product1_text)
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
            //输入库存
            $browser->pause(2000)
                ->type(ProductOne::Product['quantity'], CreProduct::Alter['low_quantity'])
            //在库存基础上数量+1  quantity_up
                ->click(ProductOne::Product['quantity_up'])
            //4.点击购买按钮
                ->press(ProductOne::Product['product_1'])
                ->pause(2000)
            //断言 understock_assert
                ->assertVisible(ProductOne::Product['understock_assert']);

        });
    }
}
