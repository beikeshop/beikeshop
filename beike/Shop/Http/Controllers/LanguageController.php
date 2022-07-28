<?php
/**
 * LanguageController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-28 16:21:14
 * @modified   2022-07-28 16:21:14
 */

namespace Beike\Shop\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function index($lang)
    {
        if (array_key_exists($lang, languages())) {
            Session::put('locale', $lang);
        }
        return Redirect::back();
    }
}
