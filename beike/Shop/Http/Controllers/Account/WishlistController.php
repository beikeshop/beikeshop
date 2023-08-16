<?php
/**
 * WishlistController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-14 20:47:04
 * @modified   2022-07-14 20:47:04
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Resources\Account\WishlistDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = CustomerRepo::wishlists(current_customer());
        $data      = [
            'wishlist' => WishlistDetail::collection($wishlists)->jsonSerialize(),
        ];

        return view('account/wishlist', $data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $productId = $request->get('product_id');
        $wishlist  = CustomerRepo::addToWishlist(current_customer(), $productId);

        return json_success(trans('shop/wishlist.add_wishlist_success'), $wishlist);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        $id = $request->id;
        CustomerRepo::removeFromWishlist(current_customer(), $id);

        return json_success(trans('shop/wishlist.remove_wishlist_success'));
    }
}
