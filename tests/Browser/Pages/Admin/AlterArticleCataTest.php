<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-08-15 14:21:04
 * @modified   2023-08-15 14:47:04
 */

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\ArticleCataData;
use Tests\Data\Admin\ArticleCataPage;
use Tests\Data\Admin\ArticlePage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class AlterArticleCataTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testAlterArticleCata()
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
                ->press(ArticlePage::Left['catalog_article'])
                //点击添加按钮
                ->click(ArticlePage::Common['edit_btn'])
                //填写基础信息
                ->type(ArticleCataPage::Cn_info['title'], ArticleCataData::Alter_Cn_info['title'])
                ->type(ArticleCataPage::Cn_info['summary'], ArticleCataData::Alter_Cn_info['summary'])
                ->click(ArticleCataPage::Top['En'])
                ->type(ArticleCataPage::En_info['title'], ArticleCataData::Alter_En_info['title'])
                ->type(ArticleCataPage::En_info['summary'], ArticleCataData::Alter_En_info['summary'])
                //点击保存
                ->press(ArticleCataPage::Common['Save_btn'])
                ->pause(3000)
                ->assertSee(ArticleCataData::Alter_Cn_info['title']);

        });
    }
}
