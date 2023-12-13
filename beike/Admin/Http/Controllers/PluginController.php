<?php
/**
 * SettingController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 16:02:15
 * @modified   2022-06-29 16:02:15
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\PluginResource;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $plugins         = app('plugin')->getPlugins();
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function shipping()
    {
        $type            = 'shipping';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function payment()
    {
        $type            = 'payment';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function total()
    {
        $type            = 'total';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function social()
    {
        $type            = 'social';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function feature()
    {
        $type            = 'feature';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function language()
    {
        $type            = 'language';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * @return mixed
     */
    public function theme()
    {
        $type            = 'theme';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    public function translator()
    {
        $type            = 'translator';
        $plugins         = app('plugin')->getPlugins();
        $plugins         = $plugins->where('type', $type);
        $data['plugins'] = array_values(PluginResource::collection($plugins)->jsonSerialize());
        $data['type']    = $type;
        $data            = hook_filter('admin.plugin.index.data', $data);

        return view('admin::pages.plugins.index', $data);
    }

    /**
     * 上传插件
     */
    public function import(Request $request): JsonResponse
    {
        $zipFile = $request->file('file');
        app('plugin')->import($zipFile);

        return json_success(trans('common.success'));
    }

    /**
     * @param Request $request
     * @param         $code
     * @return JsonResponse
     * @throws Exception
     */
    public function install(Request $request, $code): JsonResponse
    {
        try {
            $plugin = app('plugin')->getPluginOrFail($code);
            PluginRepo::installPlugin($plugin);

            return json_success(trans('common.success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param         $code
     * @return JsonResponse
     * @throws Exception
     */
    public function uninstall(Request $request, $code): JsonResponse
    {
        try {

            $plugin = app('plugin')->getPluginOrFail($code);
            PluginRepo::uninstallPlugin($plugin);

            return json_success(trans('common.success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param         $code
     * @return View
     * @throws Exception
     */
    public function edit(Request $request, $code): View
    {
        try {
            $plugin     = app('plugin')->getPluginOrFail($code);
            $columnView = $plugin->getColumnView();
            $view       = $columnView ?: 'admin::pages.plugins.form';

            $data = [
                'view'   => $view,
                'plugin' => $plugin,
            ];
            $data = hook_filter('admin.plugin.edit.data', $data);

            return view($view, $data);
        } catch (\Exception $e) {
            $plugin = app('plugin')->getPlugin($code);
            $data   = [
                'error'       => $e->getMessage(),
                'plugin_code' => $code,
                'plugin'      => $plugin,
            ];

            return view('admin::pages.plugins.error', $data);
        }
    }

    /**
     * @param Request $request
     * @param         $code
     * @return mixed
     * @throws Exception
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

        $data = ['plugin_code' => $code, 'fields' => $fields];
        hook_action('admin.plugin.update.before', $data);

        SettingRepo::update('plugin', $code, $fields);

        hook_action('admin.plugin.update.after', $data);

        return redirect($this->getRedirect())->with('success', trans('common.updated_success'));
    }

    /**
     * @param Request $request
     * @param         $code
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $code): JsonResponse
    {
        try {
            app('plugin')->getPluginOrFail($code);
            $status = $request->get('status');
            SettingRepo::update('plugin', $code, ['status' => $status]);

            return json_success(trans('common.updated_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
