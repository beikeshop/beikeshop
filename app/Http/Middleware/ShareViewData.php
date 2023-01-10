<?php
/**
 * ShareViewData.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-03 15:46:13
 * @modified   2022-08-03 15:46:13
 */

namespace App\Http\Middleware;

use Beike\Repositories\FooterRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\MenuRepo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
            $adminLanguages  = $this->handleAdminLanguages();
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
        $items     = [];
        $languages = admin_languages();
        foreach ($languages as $language) {
            $path = lang_path("{$language}/admin/base.php");
            if (file_exists($path)) {
                $baseData = require_once $path;
            }
            $name    = $baseData['name'] ?? '';
            $items[] = [
                'code' => $language,
                'name' => $name,
            ];
        }

        return $items;
    }
}
