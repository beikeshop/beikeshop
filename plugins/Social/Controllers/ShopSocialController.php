<?php
/**
 * SocialController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-30 18:46:38
 * @modified   2022-09-30 18:46:38
 */

namespace Plugin\Social\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Beike\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Plugin\Social\Repositories\CustomerRepo;

class ShopSocialController extends Controller
{
    public function initSocial()
    {
        $providerSettings = plugin_setting('social.setting', []);
        foreach ($providerSettings as $providerSetting) {
            $provider = $providerSetting['provider'];
            if (empty($provider)) {
                continue;
            }
            $config = [
                'client_id'     => $providerSetting['key'],
                'client_secret' => $providerSetting['secret'],
                'redirect'      => shop_route('social.callback', $provider, false),
            ];
            Config::set("services.{$provider}", $config);
        }
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function redirect($provider)
    {
        try {
            $this->initSocial();

            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function callback($provider)
    {
        try {
            $this->initSocial();
            $user     = Socialite::driver($provider)->user();
            $userData = [
                'uid'    => $user->getId(),
                'email'  => $user->getEmail(),
                'name'   => $user->getName(),
                'avatar' => $user->getAvatar(),
                'token'  => $user->token,
                'raw'    => $user->getRaw(),
            ];
            $customer = CustomerRepo::createCustomer($provider, $userData);
            Auth::guard(Customer::AUTH_GUARD)->login($customer);

            return view('Social::shop/callback');
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
