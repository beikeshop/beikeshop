<?php
/**
 * AttributeGroupController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-01-04 19:45:41
 * @modified   2023-01-04 19:45:41
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Repositories\AttributeGroupRepo;
use Illuminate\Http\Request;

class AttributeGroupController extends Controller
{
    public function index()
    {
        $data = [
            'attribute_groups' => AttributeGroupRepo::getList(),
        ];

        return view('admin::pages.attribute_group.index', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $item        = AttributeGroupRepo::create($requestData);

        return json_success(trans('common.created_success'), $item);
    }

    public function update(Request $request, int $id)
    {
        $requestData = json_decode($request->getContent(), true);
        $item        = AttributeGroupRepo::update($id, $requestData);

        return json_success(trans('common.updated_success'), $item);
    }

    public function destroy(Request $request, int $id)
    {
        AttributeGroupRepo::delete($id);

        return json_success(trans('common.deleted_success'));
    }
}
