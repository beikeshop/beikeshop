<?php
/**
 * BrandController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-27 21:17:04
 * @modified   2022-07-27 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\BrandRepo;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = BrandRepo::list($request->only('name', 'first', 'status'));
        $data = [
            'brands' => $brands,
        ];

        if ($request->expectsJson()) {
            return json_success('成功', $data);
        }

        return view('admin::pages.brands.index', $data);
    }

    public function store(Request $request)
    {
        $brand = BrandRepo::create($request->all());
        return json_success("创建成功", $brand);
    }

    public function update(Request $request, int $customerId, int $id)
    {
        $brand = BrandRepo::update($id, $request->all());

        return json_success("成功修改", $brand);
    }

    public function destroy(Request $request, int $customerId, int $addressId)
    {
        AddressRepo::delete($addressId);

        return json_success("已成功删除");
    }
}
