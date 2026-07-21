<?php

/*
 * @FilePath: Bootstrap.php
 *
 * @copyright     2024 beikeshop.com - All Rights Reserved.
 * @link: https://beikeshop.com
 * @Author: pu shuo <pushuo@guangda.work>
 * @Date: 2024-10-11 10:46:17
 * @LastEditTime: 2024-12-23 14:11:17
 */

/**
 * bootstrap.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-20 15:35:59
 * @modified   2022-07-20 15:35:59
 */

namespace Plugin\BkAi;

class Bootstrap
{
    public function boot()
    {
        $this->adminSettingPage();
    }

    /**
     * @return void
     */
    private function adminSettingPage(): void
    {
        add_hook_blade('admin.plugin.form', function ($callback, $output, $data) {
            $code = $data['plugin']->code;
            if ($code == 'bk_ai') {
                return view('BkAi::admin.config_form', $data)->render();
            }

            return $output;
        }, 1);

        add_hook_blade('admin.product.form.footer', function ($callback, $output, $data) {
            return view('BkAi::admin.product_form', $data)->render();
        }, 1);

        add_hook_blade('admin.file_manager.content_head.right', function ($callback, $output, $data) {
            return view('BkAi::admin.file_manager_head_right', $data)->render();
        }, 1);
    }
}
