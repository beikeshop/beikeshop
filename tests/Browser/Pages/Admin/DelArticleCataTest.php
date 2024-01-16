<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-08-15 11:37:04
 * @modified   2023-08-15 11:47:04
 */

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\ArticlePage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class DelArticleCataTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testAddArticleCata()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit(AdminLoginPage::Admin_Login['login_url'])
                //登录
                ->type(AdminLoginPage::Admin_Login['login_email'], LoginData::Ture_Data['email'])
                ->type(AdminLoginPage::Admin_Login['login_pwd'], LoginData::Ture_Data['password'])
                ->press(AdminLoginPage::Admin_Login['login_btn'])
                ->pause(2000)
                //点击文章
                ->click(AdminPage::TOP['mg_article'])
                //点击点击文章分类
                ->press(ArticlePage::Left['catalog_article']);
            //获取第一行文章分类标题
            $cata_title = $browser->text(ArticlePage::Common['cata_title_Text']);
            echo $cata_title;
            //点击删除按钮
            $browser->press(ArticlePage::Common['del_btn'])
                ->click(ArticlePage::Common['sure_btn'])
                ->pause(3000)
                ->assertDontSee($cata_title);

        });
    }
}
