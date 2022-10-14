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

use Beike\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Overtrue\Socialite\Exceptions\Exception;
use Overtrue\Socialite\SocialiteManager;
use Beike\Admin\Http\Controllers\Controller;
use Plugin\Social\Repositories\CustomerRepo;

class ShopSocialController extends Controller
{
    private SocialiteManager $socialite;

    public function __construct()
    {
        $config = [];
        $providerSettings = plugin_setting('social.setting');
        foreach ($providerSettings as $providerSetting) {
            $provider = $providerSetting['provider'];
            if (empty($provider)) {
                continue;
            }
            $config[$provider] = [
                'client_id' => $providerSetting['key'],
                'client_secret' => $providerSetting['secret'],
                'redirect' => plugin_route('social.callback', $provider),
            ];
        }
        $this->socialite = new SocialiteManager($config);
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function redirect($provider)
    {
        if (!defined(\Overtrue\Socialite\Contracts\ABNF_OPEN_ID)) {
            require_once app_path() . '/../vendor/overtrue/socialite/src/Contracts/FactoryInterface.php';
            require_once app_path() . '/../vendor/overtrue/socialite/src/Contracts/ProviderInterface.php';
            require_once app_path() . '/../vendor/overtrue/socialite/src/Contracts/UserInterface.php';
        }

        $url = $this->socialite->create($provider)->redirect();
        return redirect($url);
    }

    /**
     * @param $provider
     * @return mixed
     * @throws Exception
     */
    public function callback($provider)
    {
        $code = request('code');
        $driver = $this->socialite->create($provider);

        // For google, facebook, twitter in China.
        $driver->setGuzzleOptions([
            // 'proxy' => '127.0.0.1:7890'
        ]);

        $userData = $driver->userFromCode($code);
        $customer = CustomerRepo::createCustomer($provider, $userData);
        Auth::guard(Customer::AUTH_GUARD)->login($customer);
        return view('Social::shop/callback');
    }
}
