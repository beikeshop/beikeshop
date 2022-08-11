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
use Beike\Admin\Http\Resources\CustomerGroupDetail;
use Beike\Admin\Services\CustomerGroupService;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\LanguageRepo;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    protected string $defaultRoute = 'customer_groups.index';
    public function index(Request $request)
    {
        $customers = CustomerGroupRepo::list();

        $data = [
            'customer_groups' => $customers,
            'languages' => LanguageRepo::all(),
        ];

        return view('admin::pages.customer_groups.index', $data);
    }

    public function store(CustomerGroupRequest $request)
    {
        $customerGroup = CustomerGroupService::create($request->all());
        $customerGroup->load('descriptions', 'description');

        return json_success('创建成功！', $customerGroup);
    }

    public function update(CustomerGroupRequest $request, int $id)
    {
        $customerGroup = CustomerGroupService::update($id, $request->all());
        $customerGroup->load('descriptions', 'description');

        return json_success('更新成功！', $customerGroup);
    }

    public function destroy(Request $request, int $id)
    {
        CustomerGroupRepo::delete($id);

        return json_success('删除成功');
    }
}
