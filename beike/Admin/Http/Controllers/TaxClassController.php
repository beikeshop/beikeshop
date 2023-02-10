<?php
/**
 * TaxClassController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-26 19:45:41
 * @modified   2022-07-26 19:45:41
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Models\TaxRate;
use Illuminate\Http\Request;

class TaxClassController extends Controller
{
    public function index()
    {
        $data = [
            'tax_classes'   => TaxClassRepo::getList(),
            'all_tax_rates' => TaxRate::all(),
            'bases'         => TaxClassRepo::BASE_TYPES,
        ];

        $data = hook_filter('admin.tax_class.index.data', $data);

        return view('admin::pages.tax_classes.index', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $taxClass    = TaxClassRepo::createOrUpdate($requestData);

        return json_success(trans('common.created_success'), $taxClass);
    }

    public function update(Request $request, int $taxClassId)
    {
        $requestData       = json_decode($request->getContent(), true);
        $requestData['id'] = $taxClassId;
        $taxClass          = TaxClassRepo::createOrUpdate($requestData);

        return json_success(trans('common.updated_success'), $taxClass);
    }

    public function destroy(Request $request, int $taxClassId)
    {
        TaxClassRepo::deleteById($taxClassId);

        return json_success(trans('common.deleted_success'));
    }
}
