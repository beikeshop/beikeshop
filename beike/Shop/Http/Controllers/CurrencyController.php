<?php

/**
 * CurrencyController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-28 16:21:14
 * @modified   2022-07-28 16:21:14
 */

namespace Beike\Shop\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CurrencyController extends Controller
{
    public function index($currency)
    {
        if (in_array($currency, currencies()->where('active', true)->pluck('code')->toArray())) {
            Session::put('currency', $currency);
        }

        hook_action('currency.index.after', $currency);

        $previousUrl = url()->previous();

        $cleanUrl = remove_url_param($previousUrl, 'price');

        return Redirect::to($cleanUrl);
    }
}
