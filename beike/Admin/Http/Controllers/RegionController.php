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

class RegionController
{
    public function index()
    {
        $regions = Region::query()->with('zones')->get();
        return view('admin::pages.regions.index', ['regions' => $regions]);
    }

    public function store(Request $request)
    {
        return json_success('添加成功');
    }

    public function update(Request $request)
    {
        return json_success('更新成功');
    }

    public function destroy(Request $request)
    {
        return json_success('删除成功');
    }
}
