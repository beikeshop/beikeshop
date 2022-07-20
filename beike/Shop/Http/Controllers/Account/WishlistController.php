<?php
/**
 * WishlistController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-14 20:47:04
 * @modified   2022-07-14 20:47:04
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\AddressRequest;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Repositories\AddressRepo;
use Beike\Shop\Services\AddressService;
use Illuminate\Http\Request;
use Beike\Repositories\CountryRepo;

class WishlistController extends Controller
{
    public function index(Request $request, int $customerId)
    {
        $wishlists = CustomerRepo::wishlists($customerId, $request->get('product_id'));
        $data = [
            'wishlist' => $wishlists,
        ];

        return view('account/wishlist', $data);
    }

    public function add(Request $request, $customerId, $productId)
    {
        CustomerRepo::addToWishlist($customerId, $productId);

        $wishlists = CustomerRepo::wishlists($customerId, $request->get('product_id'));

        return json_success('加入收藏成功', $wishlists);
    }

    public function remove(Request $request, $customerId, $productId)
    {
        CustomerRepo::removeFromWishlist($customerId, $productId);

        $wishlists = CustomerRepo::wishlists($customerId, $request->get('product_id'));

        return json_success('移除收藏成功', $wishlists);
    }

}
