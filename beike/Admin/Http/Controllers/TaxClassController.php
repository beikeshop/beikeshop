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

use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Models\TaxClass;
use Beike\Models\TaxRate;
use Illuminate\Http\Request;

class TaxClassController extends Controller
{
    public function index()
    {
        $data = [
            'tax_classes' => TaxClass::query()->with([
                'taxRates.region',
                'taxRules'
            ])->get(),
            'all_tax_rates' => TaxRate::all(),
            'bases' => [
                'store',
                'payment',
                'shipping'
            ]
        ];

        return view('admin::pages.tax_classes.index', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $taxClass = TaxClassRepo::createOrUpdate($requestData);
        return json_success('保存成功', $taxClass);
    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }
}
