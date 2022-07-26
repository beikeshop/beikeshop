<?php
/**
 * TaxClassController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 19:45:41
 * @modified   2022-07-26 19:45:41
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\TaxClass;
use Illuminate\Http\Request;

class TaxClassController extends Controller
{
    public function index()
    {
        $taxClasses = TaxClass::query()->get();
        return view('admin::pages.tax_classes.index', ['tax_classes' => $taxClasses]);
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
