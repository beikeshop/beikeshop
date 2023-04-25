<?php
namespace Tests\Browser;
namespace App\Http\Controllers;


use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\By;
use function PHPUnit\Framework\assertNotEquals;

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
require_once(dirname(__FILE__) . '/../../data/admin/systemset_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/product_page.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_categories.php');
require_once(dirname(__FILE__) . '/../../data/admin/cre_categories_page.php');
//增加商品分类
class CreateCategoriesTest extends DuskTestCase
{
    public function testCancelOrder()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit(admin_login['login_url'])
        //登录后台
                ->type(admin_login['login_email'], admin_true_login['email'])
                ->type(admin_login['login_pwd'], admin_true_login['password'])
                ->press(admin_login['login_btn'])
                ->pause(2000)
                //点击商品管理
                ->click(admin_top['mg_product'])
                //点击商品分类
                ->click(products_left['product_cate'])
                ->pause(2000)
                //点击创建分类按钮
                ->press(product_cla['cre_cate_btn'])
                //填写分类信息
                ->type(categories_info['ch_name'], categories_data['ch_name'])
                ->type(categories_info['en_name'], categories_data['en_name'])
                ->type(categories_info['ch_content'], categories_data['ch_content'])
                ->type(categories_info['en_content'], categories_data['en_content'])
                ->select(categories_info['parent_cate'], 2)
                ->type(categories_info['ch_title'], categories_data['ch_title'])
                ->type(categories_info['en_title'], categories_data['en_title'])
                ->type(categories_info['ch_keywords'], categories_data['ch_keywords'])
                ->type(categories_info['en_keywords'], categories_data['en_keywords'])
                ->type(categories_info['ch_description'], categories_data['ch_description'])
                ->type(categories_info['en_description'], categories_data['en_description'])
                //点击启用
                ->click(categories_info['status_enable'])
                //点击保存
                ->press(categories_info['save_btn'])
            //跳转到前台并验证
                ->click(admin_top['root'])
                ->pause(3000)
                ->click(admin_top['go_catalog'])
                ->pause(2000)
                ->driver->switchTo()->window($browser->driver->getWindowHandles()[1]);
                $browser->click(index['top_Sports'])
                ->pause(4000)
                ->assertSee(categories_data['ch_name'])



                ;



        });
    }
}
