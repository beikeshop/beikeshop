<?php
/**
 * Bootstrap.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-10-12 17:33:29
 * @modified   2022-10-12 17:33:29
 */

namespace Plugin\Social;

use Plugin\Social\Models\CustomerSocial;

class Bootstrap
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->adminSettingPage();
        $this->deleteSocial();
        $this->addSocialData();
    }

    /**
     * @return void
     */
    private function deleteSocial(): void
    {
        add_hook_action('admin.customer.destroy.after', function ($data) {
            $customerId = $data;
            CustomerSocial::query()->where('customer_id', $customerId)->delete();
        });

    }

    /**
     * @return void
     */
    private function adminSettingPage(): void
    {
        add_hook_blade('admin.plugin.form', function ($callback, $output, $data) {
            $code = $data['plugin']->code;
            if ($code == 'social') {
                return view('Social::admin.config_form', $data)->render();
            }

            return $output;
        }, 1);
    }

    /**
     * 增加第三方登录方式
     */
    private function addSocialData(): void
    {
        add_hook_filter('login.social.buttons', function ($buttons) {
            $providers = plugin_setting('social.setting');
            if (empty($providers)) {
                return $buttons;
            }

            foreach ($providers as $provider) {
                $status = (bool) ($provider['status'] ?? false);
                if (! $status) {
                    continue;
                }
                $buttons[] = view('Social::shop/social_button', ['provider' => $provider])->render();
            }

            return $buttons;
        });
    }
}
