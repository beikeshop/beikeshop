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
        $plugins = app('plugin')->getPlugins();
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        return view('admin::pages.plugins.index', $data);
    }


    /**
     * 上传插件
     */
    public function import(Request $request): array
    {
        $zipFile = $request->file('file');
        app('plugin')->import($zipFile);
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
        $plugin = app('plugin')->getPluginOrFail($code);
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
        $plugin = app('plugin')->getPluginOrFail($code);
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
        $plugin = app('plugin')->getPluginOrFail($code);
        $columnView = $plugin->getColumnView();
        $view = $columnView ?: 'admin::pages.plugins.form';
        return view($view, ['plugin' => $plugin]);
    }


    /**
     * @param Request $request
     * @param $code
     * @return mixed
     */
    public function update(Request $request, $code)
    {
        $fields = $request->all();
        $plugin = app('plugin')->getPluginOrFail($code);
        if (method_exists($plugin, 'validate')) {
            $validator = $plugin->validate($fields);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }

        SettingRepo::update('plugin', $code, $fields);
        return redirect($this->getRedirect())->with('success', '修改成功');
    }


    /**
     * @param Request $request
     * @param $code
     * @return array
     * @throws Exception
     */
    public function updateStatus(Request $request, $code): array
    {
        app('plugin')->getPluginOrFail($code);
        $status = $request->get('status');
        SettingRepo::update('plugin', $code, ['status' => $status]);
        return json_success("编辑成功");
    }
}
