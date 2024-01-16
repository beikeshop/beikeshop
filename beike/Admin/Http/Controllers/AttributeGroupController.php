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
use Exception;
use Illuminate\Http\Request;

class AttributeGroupController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'attribute_groups' => AttributeGroupRepo::getList(),
            ];
            $data = hook_filter('admin.attribute_group.index.data', $data);
        } catch (Exception $e) {
            return view('admin::pages.attribute_group.index', $data)->withErrors(['error' => $e->getMessage()]);
        }

        return view('admin::pages.attribute_group.index', $data);
    }

    public function store(Request $request)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeGroupRepo::create($requestData);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.created_success'), $item);
    }

    public function update(Request $request, int $id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeGroupRepo::update($id, $requestData);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.updated_success'), $item);
    }

    public function destroy(Request $request, int $id)
    {
        try {
            AttributeGroupRepo::delete($id);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.deleted_success'));
    }
}
