<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-27 21:17:04
 * @modified   2022-07-27 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\BrandRepo;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * 显示品牌列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $brands = BrandRepo::list($request->only('name', 'first', 'status'));
        $data = [
            'brands' => $brands,
        ];

        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.brands.index', $data);
    }


    /**
     * 创建品牌
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        $brand = BrandRepo::create($request->all());
        return json_success(trans('common.created_success'), $brand);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): array
    {
        $brand = BrandRepo::update($id, $request->all());

        return json_success(trans('common.updated_success'), $brand);
    }

    public function destroy(int $addressId): array
    {
        BrandRepo::delete($addressId);

        return json_success(trans('common.deleted_success'));
    }

    public function autocomplete(Request $request): array
    {
        $brands = BrandRepo::autocomplete($request->get('name') ?? '', 0);

        return json_success(trans('common.get_success'), $brands);
    }


    public function name(int $id): array
    {
        $name = BrandRepo::getName($id);

        return json_success(trans('common.get_success'), $name);
    }


    /**
     * 根据商品ID批量获取商品名称
     *
     * @param Request $request
     * @return array
     */
    public function getNames(Request $request): array
    {
        $ids = explode(',', $request->get('ids'));
        $name = BrandRepo::getNames($ids);

        return json_success(trans('common.get_success'), $name);
    }
}
