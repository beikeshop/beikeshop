<?php
/**
 * CustomerGroupController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 21:17:04
 * @modified   2022-06-30 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CustomerGroupRequest;
use Beike\Admin\Services\CustomerGroupService;
use Beike\Repositories\CustomerGroupRepo;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    protected string $defaultRoute = 'customer_groups.index';
    public function index(Request $request)
    {
        $customers = CustomerGroupRepo::list();

        $data = [
            'customer_groups' => $customers,
        ];

        return view('admin::pages.customer_groups.index', $data);
    }

    public function store(CustomerGroupRequest $request)
    {
        $data = [
            'total' => (int)$request->get('total', 0),
            'reward_point_factor' => (float)$request->get('reward_point_factor', 0),
            'use_point_factor' => (float)$request->get('use_point_factor', 0),
            'discount_factor' => (float)$request->get('discount_factor', 0),
            'level' => (int)$request->get('level', 0),
            'descriptions' => $request->get('descriptions', [])
        ];
        $customerGroup = CustomerGroupService::create($data);

        return json_success('创建成功！', $customerGroup);
    }

    public function update(CustomerGroupRequest $request, int $id)
    {
        $data = [
            'total' => (int)$request->get('total', 0),
            'reward_point_factor' => (float)$request->get('reward_point_factor', 0),
            'use_point_factor' => (float)$request->get('use_point_factor', 0),
            'discount_factor' => (float)$request->get('discount_factor', 0),
            'level' => (int)$request->get('level', 0),
            'descriptions' => $request->get('descriptions', [])
            ];
        $customerGroup = CustomerGroupService::update($id, $data);

        return json_success('更新成功！', $customerGroup);
    }

    public function destroy(Request $request, int $id)
    {
        CustomerGroupRepo::delete($id);

        return json_success('删除成功');
    }
}
