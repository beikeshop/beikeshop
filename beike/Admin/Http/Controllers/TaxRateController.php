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

use Beike\Models\Region;
use Illuminate\Http\Request;
use Beike\Admin\Repositories\TaxRateRepo;

class TaxRateController
{
    public function index()
    {
        $data = [
            'tax_rates' => TaxRateRepo::getList(),
            'regions' => Region::all()
        ];

        return view('admin::pages.tax_rates.index', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $taxRate = TaxRateRepo::createOrUpdate($requestData);
        $taxRate->load('region');
        return json_success('保存成功', $taxRate);
    }

    public function update(Request $request, int $taxRateId)
    {
        $requestData = json_decode($request->getContent(), true);
        $requestData['id'] = $taxRateId;
        $taxRate = TaxRateRepo::createOrUpdate($requestData);
        $taxRate->load('region');
        return json_success('更新成功', $taxRate);
    }

    public function destroy(Request $request, int $taxRateId)
    {
        TaxRateRepo::deleteById($taxRateId);
        return json_success('删除成功');
    }
}
