<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-08-15 11:17:04
 * @modified   2023-08-15:17:04
 */

namespace Tests\Browser\Pages\Admin;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use Laravel\Dusk\Browser;
use Tests\Data\Admin\AdminLoginPage;
use Tests\Data\Admin\AdminPage;
use Tests\Data\Admin\ArticleData;
use Tests\Data\Admin\ArticlePage;
use Tests\Data\Admin\LoginData;
use Tests\DuskTestCase;

class AlterArticleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @return void
     */
    public function testAlterArticle()
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
                //点击点击文章管理
                ->press(ArticlePage::Left['mg_article'])
                //点击编辑按钮
                ->click(ArticlePage::Common['edit_btn'])
                ->press(ArticlePage::Top['Cn'])
                //填写基础信息
                ->type(ArticlePage::Cn_info['title'], ArticleData::Alter_Cn_info['title'])
                ->type(ArticlePage::Cn_info['summary'], ArticleData::Alter_Cn_info['summary']);
            //切换窗口
            //填写卡号
            $wait              = new WebDriverWait($browser->driver, 10);
            $iframeElementTab1 = $wait->until(function () use ($browser) {
                return $browser->driver->findElement(WebDriverBy::cssSelector('#mce_0_ifr'));
            });
            // 切换到 tab1 下的 iframe
            $browser->driver->switchTo()->frame($iframeElementTab1);
            // 在 tab1 下的 iframe 中查找并修改第一个 <p> 元素的文本
            $paragraphTab1 = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::tagName('p')
            ));
            $cn_content = ArticleData::Alter_Cn_info['content'];
            $browser->driver->executeScript("
                    var paragraphTab1 = arguments[0];
                    if (paragraphTab1) {
                    paragraphTab1.innerHTML = '$cn_content';
                }
                ", [$paragraphTab1]);
            $browser->driver->switchTo()->defaultContent();
            $browser->pause(2000);
            $browser->driver->switchTo()->defaultContent();
            $browser->press(ArticlePage::Top['En'])
                ->type(ArticlePage::En_info['title'], ArticleData::Alter_En_info['title'])
                ->type(ArticlePage::En_info['summary'], ArticleData::Alter_Cn_info['summary']);
            //切换窗口、填写内容
            $wait              = new WebDriverWait($browser->driver, 10);
            $iframeElementTab2 = $wait->until(function () use ($browser) {
                return $browser->driver->findElement(WebDriverBy::cssSelector('#mce_2_ifr'));
            });

            // 切换到 tab2 下的 iframe
            $browser->driver->switchTo()->frame($iframeElementTab2);
            $browser->pause(2000);

            // 在 tab2 下的 iframe 中查找并修改第一个 <p> 元素的文本
            $paragraphTab2 = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::tagName('p')
            ));
            $en_content = ArticleData::Alter_En_info['content'];
            $browser->driver->executeScript("
                    var paragraphTab2 = arguments[0];
                    if (paragraphTab2) {
                    paragraphTab2.innerHTML = '$en_content';
                }
                ", [$paragraphTab2]);
            $browser->driver->switchTo()->defaultContent();
            $browser->pause(2000);
            //5.点击保存
            $browser->press(ArticlePage::Common['save_btn'])
                ->pause(2000)
                ->assertSee(ArticleData::Cn_info['title']);
        });
    }
}
