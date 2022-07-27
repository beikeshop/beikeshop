<?php
/**
 * RegionZoneController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-26 20:01:02
 * @modified   2022-07-26 20:01:02
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Region;
use Illuminate\Http\Request;
use Beike\Repositories\CountryRepo;
use Beike\Admin\Repositories\RegionRepo;

class RegionController
{
    public function index()
    {
        $data = [
            'regions' => Region::query()->with('regionZones.zone')->get(),
            'countries' => CountryRepo::all()
        ];

        return view('admin::pages.regions.index', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $region = RegionRepo::createOrUpdate($requestData);
        return json_success('保存成功', $region);
    }

    public function update(Request $request, int $regionId)
    {
        $requestData = json_decode($request->getContent(), true);
        $requestData['id'] = $regionId;
        $region = RegionRepo::createOrUpdate($requestData);
        return json_success('更新成功', $region);
    }

    public function destroy(Request $request, int $regionId)
    {
        RegionRepo::deleteById($regionId);
        return json_success('删除成功');
    }
}
