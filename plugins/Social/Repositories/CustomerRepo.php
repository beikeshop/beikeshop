<?php
/**
 * CustomerRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-10-13 09:57:13
 * @modified   2022-10-13 09:57:13
 */

namespace Plugin\Social\Repositories;

use Beike\Models\Customer;
use Beike\Shop\Services\AccountService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;
use Plugin\Social\Models\CustomerSocial;

class CustomerRepo
{
    /**
     * 允许的第三方服务
     */
    private const PROVIDERS = [
        'facebook',
        'twitter',
        'google',
    ];

    public static function allProviders(): array
    {
        $items = [];
        foreach (self::PROVIDERS as $provider) {
            $items[] = [
                'code'  => $provider,
                'label' => trans("Social::providers.{$provider}"),
            ];
        }

        return $items;
    }

    /**
     * 创建客户, 关联第三方用户数据
     *
     * @param      $provider
     * @param User $userData
     * @return Customer
     */
    public static function createCustomer($provider, User $userData): Customer
    {
        $social   = self::getCustomerByProvider($provider, $userData->getId());
        $customer = $social->customer ?? null;
        if ($customer) {
            return $customer;
        }

        $email = $userData->getEmail();
        if (empty($email)) {
            $email = strtolower(Str::random(8)) . "@{$provider}.com";
        }
        $customer = Customer::query()->where('email', $email)->first();
        if (empty($customer)) {
            $customerData = [
                'from'   => $provider,
                'email'  => $email,
                'name'   => $userData->getName(),
                'avatar' => $userData->getAvatar(),
            ];
            $customer = AccountService::register($customerData);
        }

        self::createSocial($customer, $provider, $userData);

        return $customer;
    }

    /**
     * @param      $customer
     * @param      $provider
     * @param User $userData
     * @return Model|Builder
     */
    public static function createSocial($customer, $provider, User $userData): Model|Builder
    {
        $social = self::getCustomerByProvider($provider, $userData->getId());
        if ($social) {
            return $social;
        }

        $socialData = [
            'customer_id'  => $customer->id,
            'provider'     => $provider,
            'user_id'      => $userData->getId(),
            'union_id'     => '',
            'access_token' => $userData->token,
            'extra'        => json_encode($userData->getRaw()),
        ];

        return CustomerSocial::query()->create($socialData);
    }

    /**
     * 通过 provider 和 user_id 获取已存在 social
     * @param $provider
     * @param $userId
     * @return Model|Builder|null
     */
    private static function getCustomerByProvider($provider, $userId): Model|Builder|null
    {
        return CustomerSocial::query()
            ->with(['customer'])
            ->where('provider', $provider)
            ->where('user_id', $userId)
            ->first();
    }
}
