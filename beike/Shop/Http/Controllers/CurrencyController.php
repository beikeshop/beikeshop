<?php
/**
 * CurrencyController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-28 16:21:14
 * @modified   2022-07-28 16:21:14
 */

namespace Beike\Shop\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CurrencyController extends Controller
{
    public function index($lang)
    {
        if (in_array($lang, currencies()->where('status', true)->pluck('code')->toArray())) {
            Session::put('currency', $lang);
        }
        return Redirect::back();
    }
}
