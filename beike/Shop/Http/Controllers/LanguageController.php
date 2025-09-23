<?php
/**
 * LanguageController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-28 16:21:14
 * @modified   2022-07-28 16:21:14
 */

namespace Beike\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function index($lang)
    {
        if (in_array($lang, languages()->toArray())) {
            Session::put('locale', $lang);
        }

        hook_action('language.index.after', $lang);

        return $this->redirectUrl();

    }

    /**
     *  redirect url
     *
     * @return RedirectResponse
     */
    private function redirectUrl(): RedirectResponse
    {
        $lang    = Session::get('locale');
        $backUrl = redirect()->back()->getTargetUrl();
        $host    = get_base_url($backUrl);
        $uri     = str_replace($host, '', $backUrl);

        if ($uri == '/') {
            $uri = $uri . $lang;
        } else {
            foreach (config('app.langs') as $item) {
                $uriArr = explode('/', $uri);

                if (count($uriArr) && in_array($item, $uriArr)) {
                    // 使用正则表达式替换完整的语言部分
                    $uri = preg_replace('/\/(' . preg_quote($item) . ')(\/|$)/', '/'.$lang.'$2', $uri);

                    if (locale() === system_setting('base.locale')) {
                        $uri = str_replace('/' . locale(), '', $uri);
                    }
                    break;
                }

                if (count($uriArr) && (isset($uriArr[1]) && ! in_array($uriArr[1], config('app.langs')))) {
                    if (locale() !== system_setting('base.locale')) {
                        $uri = '/' . $lang . $uri;
                        break;
                    }
                }
            }
        }

        return redirect()->to($host . $uri);
    }
}
