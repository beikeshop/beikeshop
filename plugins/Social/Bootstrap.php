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


class Bootstrap
{
    public function boot()
    {
        $this->addSocialData();
    }

    /**
     * 增加第三方登录方式
     */
    private function addSocialData()
    {
        add_filter('login.social.buttons', function ($buttons) {
            $providers = plugin_setting('social.setting');
            if (empty($providers)) {
                return $buttons;
            }

            foreach ($providers as $provider) {
                $buttons[] = view('Social::shop/social_button', ['provider' => $provider])->render();
            }
            return $buttons;
        });
    }
}
