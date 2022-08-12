<?php
/*
 * @copyright     2022 opencart.cn - All Rights Reserved.
 * @link          https://www.guangdawangluo.com
 * @Author        PS <pushuo@opencart.cn>
 * @Date          2022-08-02 19:19:52
 * @LastEditTime  2022-08-10 19:07:07
 */

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
            return json_success('成功', $data);
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
        return json_success("创建成功", $brand);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): array
    {
        $brand = BrandRepo::update($id, $request->all());

        return json_success("成功修改", $brand);
    }

    public function destroy(int $addressId): array
    {
        BrandRepo::delete($addressId);

        return json_success("已成功删除");
    }

    public function autocomplete(Request $request): array
    {
        $brands = BrandRepo::autocomplete($request->get('name') ?? '', 0);

        return json_success('获取成功！', $brands);
    }


    public function name(int $id): array
    {
        $name = BrandRepo::getName($id);

        return json_success('获取成功', $name);
    }


    /**
     * 根据产品ID批量获取产品名称
     *
     * @param Request $request
     * @return array
     */
    public function getNames(Request $request): array
    {
        $ids = explode(',', $request->get('ids'));
        $name = BrandRepo::getNames($ids);

        return json_success('获取成功', $name);
    }
}
