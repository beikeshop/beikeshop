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

use Beike\Repositories\LanguageRepo;

class LanguageController extends Controller
{
    public function index($lang)
    {
        if (array_key_exists($lang, LanguageRepo::all()->where('status', true)->pluck('code'))) {
            Session::put('locale', $lang);
        }
        return Redirect::back();
    }
}
