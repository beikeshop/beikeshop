<?php
/**
 * TaxRateController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 20:00:13
 * @modified   2022-07-26 20:00:13
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController
{
    public function index()
    {
        $taxRates = TaxRate::all();
        return view('admin::pages.tax_rates.index', ['tax_rates' => $taxRates]);
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }
}
