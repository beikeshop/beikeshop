<?php
/**
 * LanguageController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-04 16:21:14
 * @modified   2022-08-04 16:21:14
 */

namespace Beike\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function index($lang)
    {
        if (in_array($lang, languages()->toArray())) {
           current_user()->locale = $lang;
            current_user()->save();
        }
        return Redirect::back();
    }
}
