<?php

namespace Plugin\Upsell;

use Illuminate\Support\Facades\Log;

class Bootstrap
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->adminSettingPage();
        Log::info("Upsell 插件启动了");
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
                return view('Upsell::admin.config_form', $data)->render();
            }

            return $output;
        }, 1);
    }
}
