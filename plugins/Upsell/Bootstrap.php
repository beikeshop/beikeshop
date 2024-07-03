<?php

namespace Plugin\Upsell;

use Illuminate\Support\Facades\Log;
use Plugin\Upsell\Models\MarketingActivity;

class Bootstrap
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->adminSettingPage();
    }

    /**
     * 启用自定义设置页面
     * @return void
     */
    private function adminSettingPage(): void
    {
        add_hook_blade('admin.plugin.form', function ($callback, $output, $data) {
            $code = $data['plugin']->code;
            if ($code == 'upsell') {
                Log::info("Upsell 插件启动了");
                $activities = $this->loadMarketingActivities();
                foreach ($activities as $item) {
                    Log::info($item);
                }
                $data['activities'] = $activities;
                return view('Upsell::admin.config_form', $data)->render();
            }

            return $output;
        }, 1);
    }

    private function loadMarketingActivities()
    {
        return MarketingActivity::all();
    }

}
