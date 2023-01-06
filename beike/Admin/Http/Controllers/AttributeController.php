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
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $attributes = AttributeRepo::getList();
        $data = [
            'attribute_list' => $attributes,
            'attribute_list_format' => AttributeResource::collection($attributes),
            'attribute_group' => AttributeGroupRepo::getList(),
        ];

        if ($request->expectsJson()) {
            return json_success(trans('success'), $data);
        }

        return view('admin::pages.attributes.index', $data);
    }

    public function show(Request $request, int $id)
    {
        $data = [
            'attribute' => (new AttributeDetailResource(AttributeRepo::find($id)))->jsonSerialize(),
            'attribute_group' => AttributeGroupRepo::getList(),
        ];

        return view('admin::pages.attributes.form', $data);
    }

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $item = AttributeRepo::create($requestData);
        return json_success(trans('common.created_success'), $item);
    }

    public function update(Request $request, int $id)
    {
        $requestData = json_decode($request->getContent(), true);
        $item = AttributeRepo::update($id, $requestData);
        return json_success(trans('common.updated_success'), $item);
    }


    public function storeValue(Request $request, int $id)
    {
        $requestData = json_decode($request->getContent(), true);
        $item = AttributeRepo::createValue(array_merge($requestData, ['attribute_id' => $id]));
        return json_success(trans('common.created_success'), new AttributeValueResource($item));
    }

    public function updateValue(Request $request, int $id, int $value_id)
    {
        $requestData = json_decode($request->getContent(), true);
        $item = AttributeRepo::updateValue($value_id, $requestData);
        return json_success(trans('common.updated_success'), new AttributeValueResource($item));
    }

    public function destroyValue(Request $request, int $id, int $value_id)
    {
        AttributeRepo::deleteValue($value_id);
        return json_success(trans('common.deleted_success'));
    }

    public function destroy(Request $request, int $id)
    {
        AttributeRepo::delete($id);
        return json_success(trans('common.deleted_success'));
    }

    public function autocomplete(Request $request): array
    {
        $items = AttributeRepo::autocomplete($request->get('name') ?? '', 0);

        return json_success(trans('common.get_success'), AutocompleteResource::collection($items));
    }

    public function autocompleteValue(Request $request, int $id): array
    {
        $items = AttributeRepo::autocompleteValue($id, $request->get('name') ?? '');

        return json_success(trans('common.get_success'), AutocompleteResource::collection($items));
    }
}
