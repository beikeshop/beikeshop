<?php
/**
 * SettingController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 16:02:15
 * @modified   2022-06-29 16:02:15
 */

namespace Beike\Admin\Http\Controllers;

use Exception;
use Beike\Plugin\Manager;
use Illuminate\Http\Request;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Illuminate\Contracts\View\View;
use Beike\Admin\Http\Resources\PluginResource;

class PluginController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $plugins = (new Manager)->getPlugins();
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        return view('admin::pages.plugins.index', $data);
    }


    /**
     * 上传插件
     */
    public function import(Request $request): array
    {
        return json_success("导入成功");
    }


    /**
     * @param Request $request
     * @param $code
     * @return array
     * @throws Exception
     */
    public function install(Request $request, $code): array
    {
        $plugin = (new Manager)->getPluginOrFail($code);
        PluginRepo::installPlugin($plugin);
        return json_success("安装成功");
    }


    /**
     * @param Request $request
     * @param $code
     * @return array
     * @throws Exception
     */
    public function uninstall(Request $request, $code): array
    {
        $plugin = (new Manager)->getPluginOrFail($code);
        PluginRepo::uninstallPlugin($plugin);
        return json_success("卸载成功");
    }


    /**
     * @param Request $request
     * @param $code
     * @return View
     * @throws Exception
     */
    public function edit(Request $request, $code): View
    {
        $data['plugin'] = (new Manager)->getPluginOrFail($code);
        return view('admin::pages.plugins.form', $data);
    }


    /**
     * @param Request $request
     * @param $code
     * @return array
     * @throws Exception
     */
    public function update(Request $request, $code): array
    {
        (new Manager)->getPluginOrFail($code);
        $fields = $request->all();
        SettingRepo::update('plugin', $code, $fields);
        return json_success("编辑成功");
    }


    /**
     * @param Request $request
     * @param $code
     * @return array
     * @throws Exception
     */
    public function updateStatus(Request $request, $code): array
    {
        (new Manager)->getPluginOrFail($code);
        $status = $request->get('status');
        SettingRepo::update('plugin', $code, ['status' => $status]);
        return json_success("编辑成功");
    }
}
