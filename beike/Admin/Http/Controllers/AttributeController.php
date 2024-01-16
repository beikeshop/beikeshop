<?php
/**
 * AttributeController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-01-04 19:45:41
 * @modified   2023-01-04 19:45:41
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\AttributeDetailResource;
use Beike\Admin\Http\Resources\AttributeResource;
use Beike\Admin\Http\Resources\AttributeValueResource;
use Beike\Admin\Http\Resources\AutocompleteResource;
use Beike\Admin\Repositories\AttributeGroupRepo;
use Beike\Admin\Repositories\AttributeRepo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $attributes = AttributeRepo::getList();
            $data       = [
                'attribute_list'        => $attributes,
                'attribute_list_format' => AttributeResource::collection($attributes),
                'attribute_group'       => AttributeGroupRepo::getList(),
            ];
            $data = hook_filter('admin.attribute.index.data', $data);
            if ($request->expectsJson()) {
                return json_success(trans('success'), $data);
            }
        } catch (Exception $e) {
            return view('admin::pages.attributes.index', $data)->withErrors(['error' => $e->getMessage()]);
        }

        return view('admin::pages.attributes.index', $data);
    }

    public function show(Request $request, int $id)
    {
        try {
            $data = [
                'attribute'       => (new AttributeDetailResource(AttributeRepo::find($id)))->jsonSerialize(),
                'attribute_group' => AttributeGroupRepo::getList(),
            ];
            $data = hook_filter('admin.attribute.show.data', $data);
        } catch (Exception $e) {
            return view('admin::pages.attributes.form', $data)->withErrors(['error' => $e->getMessage()]);
        }

        return view('admin::pages.attributes.form', $data);
    }

    public function store(Request $request)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeRepo::create($requestData);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.created_success'), $item);
    }

    public function update(Request $request, int $id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeRepo::update($id, $requestData);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.updated_success'), $item);
    }

    public function storeValue(Request $request, int $id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeRepo::createValue(array_merge($requestData, ['attribute_id' => $id]));
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.created_success'), new AttributeValueResource($item));
    }

    public function updateValue(Request $request, int $id, int $value_id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $item        = AttributeRepo::updateValue($value_id, $requestData);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.updated_success'), new AttributeValueResource($item));
    }

    public function destroyValue(Request $request, int $id, int $value_id)
    {
        try {
            AttributeRepo::deleteValue($value_id);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.deleted_success'));
    }

    public function destroy(Request $request, int $id)
    {
        try {
            AttributeRepo::delete($id);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.deleted_success'));
    }

    public function autocomplete(Request $request): JsonResponse
    {
        try {
            $items = AttributeRepo::autocomplete($request->get('name') ?? '', 0);
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.get_success'), AutocompleteResource::collection($items));
    }

    public function autocompleteValue(Request $request, int $id): JsonResponse
    {
        try {
            $items = AttributeRepo::autocompleteValue($id, $request->get('name') ?? '');
        } catch (Exception $e) {
            return json_fail($e->getMessage(), []);
        }

        return json_success(trans('common.get_success'), AutocompleteResource::collection($items));
    }
}
