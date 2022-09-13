<?php
/**
 * CustomerGroupController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 21:17:04
 * @modified   2022-06-30 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Admin\Services\CustomerGroupService;
use Beike\Admin\Http\Requests\CustomerGroupRequest;

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

        return json_success(trans('common.created_success'), $customerGroup);
    }

    public function update(CustomerGroupRequest $request, int $id)
    {
        $customerGroup = CustomerGroupService::update($id, $request->all());
        $customerGroup->load('descriptions', 'description');

        return json_success(trans('common.updated_success'), $customerGroup);
    }

    public function destroy(Request $request, int $id)
    {
        CustomerGroupRepo::delete($id);
        return json_success(trans('common.deleted_success'));
    }
}
