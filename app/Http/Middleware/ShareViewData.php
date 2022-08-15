<?php
/*
 * @copyright     2022 opencart.cn - All Rights Reserved.
 * @link          https://www.guangdawangluo.com
 * @Author        PS <pushuo@opencart.cn>
 * @Date          2022-08-10 16:45:58
 * @LastEditTime  2022-08-10 17:03:40
 */

/**
 * ShareViewData.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-03 15:46:13
 * @modified   2022-08-03 15:46:13
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Beike\Repositories\MenuRepo;
use Beike\Repositories\FooterRepo;
use Illuminate\Support\Facades\View;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\LanguageRepo;

class ShareViewData
{
    public function handle(Request $request, Closure $next)
    {
        $this->loadShopShareViewData();
        return $next($request);
    }

    /**
     * @throws \Exception
     */
    protected function loadShopShareViewData()
    {
        if (is_admin()) {
            $adminLanguages = $this->handleAdminLanguages();
            $loggedAdminUser = current_user();
            if ($loggedAdminUser) {
                $currentLanguage = $loggedAdminUser->locale ?: 'en';
                View::share('admin_languages', $adminLanguages);
                View::share('admin_language', collect($adminLanguages)->where('code', $currentLanguage)->first());
            }
        } else {
            View::share('design', request('design') == 1);
            View::share('languages', LanguageRepo::enabled());
            View::share('shop_base_url', shop_route('home.index'));
            View::share('categories', hook_filter('header.categories', CategoryRepo::getTwoLevelCategories()));
            View::share('footer_content', hook_filter('footer.content', FooterRepo::handleFooterData()));
            View::share('menu_content', hook_filter('menu.content', MenuRepo::handleMenuData()));
        }
    }


    /**
     * 处理后台语言包列表
     *
     * @return array
     */
    private function handleAdminLanguages(): array
    {
        $items = [];
        $languages = admin_languages();
        foreach ($languages as $language) {
            $path = lang_path("{$language}/admin/base.php");
            if (file_exists($path)) {
                $baseData = require_once($path);
            }
            $name = $baseData['name'] ?? '';
            $items[] = [
                'code' => $language,
                'name' => $name,
            ];
        }
        return $items;
    }
}
