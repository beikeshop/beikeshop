<?php
/**
 * AdminAuthenticate.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 14:44:54
 * @modified   2023-04-20 14:44:54
 */

namespace App\Http\Middleware;

use Beike\Models\AdminUser;
use Beike\Repositories\AdminUserTokenRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\UnauthorizedException;

class AdminApiAuthenticate
{
    public const ADMIN_API_PREFIX = 'admin_api.';

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $token = $request->header('token');
        if (empty($token)) {
            $token = $request->get('token');
        }

        $token = AdminUserTokenRepo::getAdminUserTokenByToken($token);
        if (empty($token)) {
            throw new UnauthorizedException(trans('customer.unauthorized_without_token'));
        }

        $adminUser = $token->adminUser;
        if (! $this->hasPermission($adminUser)) {
            throw new UnauthorizedException(trans('customer.unauthorized_without_permission'));
        }

        register('admin_user', $adminUser);

        return $next($request);
    }

    private function hasPermission(AdminUser $adminUser): bool
    {
        // $routeUri = Route::current()->uri();
        $routeName = Route::currentRouteName();

        $routePath = str_replace(self::ADMIN_API_PREFIX, '', $routeName);
        if ($routePath == 'me') {
            return true;
        }

        $permissionName = $this->mapPermissionByRoute($routePath);
        if (empty($permissionName)) {
            return false;
        }

        return $adminUser->can($permissionName);
    }

    private function mapPermissionByRoute($routePath): string
    {
        $maps = [
            'categories.index'  => 'categories_index',
            'categories.show'   => 'categories_show',
            'categories.create' => 'categories_create',
            'categories.update' => 'categories_update',
            'categories.delete' => 'categories_delete',

            'brands.index'  => 'brands_index',
            'brands.show'   => 'brands_show',
            'brands.create' => 'brands_create',
            'brands.update' => 'brands_update',
            'brands.delete' => 'brands_delete',

            'orders.index'           => 'orders_index',
            'orders.show'            => 'orders_show',
            'orders.update_status'   => 'orders_update_status',
            'orders.update_shipment' => 'orders_update_status',

            'products.index'  => 'products_index',
            'products.show'   => 'products_show',
            'products.create' => 'products_create',
            'products.update' => 'products_update',
            'products.delete' => 'products_delete',
        ];

        return $maps[$routePath] ?? '';
    }
}
