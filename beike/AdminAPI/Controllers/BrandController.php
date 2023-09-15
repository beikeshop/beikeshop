<?php
/**
 * BrandController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 17:19:51
 * @modified   2023-04-20 17:19:51
 */

namespace Beike\AdminAPI\Controllers;

use Beike\Models\Brand;
use Beike\Repositories\BrandRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController
{
    /**
     * 显示品牌列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $brands = BrandRepo::list($request->only('name', 'first', 'status'));
        $data   = [
            'brands' => $brands,
        ];

        return hook_filter('admin_api.brand.index.data', $data);
    }

    /**
     * 创建品牌
     *
     * @param Request $request
     * @param Brand   $brand
     * @return Brand
     */
    public function show(Request $request, Brand $brand): Brand
    {
        return hook_filter('admin_api.brand.show.data', $brand);
    }

    /**
     * 创建品牌
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $requestData = $request->all();
        $data        = [
            'request_data' => $requestData,
        ];

        hook_action('admin_api.brand.store.before', $data);
        $brand = BrandRepo::create($requestData);
        hook_action('admin_api.brand.store.after', ['brand' => $brand, 'request_data' => $requestData]);

        return json_success(trans('common.created_success'), $brand);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, Brand $brand): JsonResponse
    {
        $requestData = $request->all();
        $data        = [
            'brand_id'     => $brand,
            'request_data' => $requestData,
        ];
        hook_action('admin_api.brand.update.before', $data);
        $brand = BrandRepo::update($brand, $requestData);
        hook_action('admin_api.brand.update.after', $data);

        return json_success(trans('common.updated_success'), $brand);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @return JsonResponse
     */
    public function destroy(Request $request, Brand $brand): JsonResponse
    {
        hook_action('admin_api.brand.destroy.before', $brand);
        BrandRepo::delete($brand);
        hook_action('admin_api.brand.destroy.after', $brand);

        return json_success(trans('common.deleted_success'));
    }
}
