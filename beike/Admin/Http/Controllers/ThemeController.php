<?php
/**
 * ThemeController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-16 12:00:13
 * @modified   2023-03-16 12:00:13
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Database\Seeders\ThemeSeeder;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Themes index
     *
     * @return mixed
     * @throws \Exception
     */
    public function index()
    {
        $currentTheme = system_setting('base.theme');
        $themes       = PluginRepo::getEnabledThemes();

        $data['themes'][] = [
            'name'   => trans('admin/theme.theme_name'),
            'code'   => 'default',
            'demo'   => true,
            'image'  => image_origin('image/default-theme.jpg'),
            'status' => $currentTheme == 'default',
        ];

        foreach ($themes as $theme) {
            $themeCode        = $theme->code;
            $plugin           = $theme->plugin;
            $imagePath        = $plugin->theme ?? 'image/theme.jpg';
            $data['themes'][] = [
                'name'   => $plugin->getLocaleName(),
                'code'   => $themeCode,
                'demo'   => true,
                'image'  => plugin_asset($themeCode, $imagePath),
                'status' => $currentTheme == $themeCode,
            ];
        }

        return view('admin::pages.theme.index', $data);
    }

    /**
     * Enable theme
     *
     * @param Request $request
     * @param         $themeCode
     * @return mixed
     * @throws \Exception
     */
    public function update(Request $request, $themeCode)
    {
        $importDemo = $request->get('import_demo');
        if ($importDemo) {
            if ($themeCode == 'default') {
                $seeder = new ThemeSeeder();
                $seeder->run();
            } else {
                $plugin = plugin($themeCode);
                if ($plugin) {
                    PluginRepo::runSeeder($plugin);
                }
            }
        }

        SettingRepo::update('system', 'base', ['theme' => $themeCode]);

        return json_success(trans('common.success'));
    }
}
