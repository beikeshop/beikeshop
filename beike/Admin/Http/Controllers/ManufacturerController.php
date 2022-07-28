<?php
/**
 * ManufacturerController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-27 21:17:04
 * @modified   2022-07-27 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\ManufacturerRepo;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $manufacturers = ManufacturerRepo::list($request->only('name', 'first', 'status'));
        $data = [
            'manufacturers' => $manufacturers,
        ];

        return view('admin::pages.manufacturers.index', $data);
    }

    public function store(Request $request)
    {
        $manufacturer = ManufacturerRepo::create($request->all());
        return json_success("创建成功", $manufacturer);
    }

    public function update(Request $request, int $customerId, int $id)
    {
        $manufacturer = ManufacturerRepo::update($id, $request->all());

        return json_success("成功修改", $manufacturer);
    }

    public function destroy(Request $request, int $customerId, int $addressId)
    {
        AddressRepo::delete($addressId);

        return json_success("已成功删除");
    }
}
