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
use Beike\Shop\Http\Resources\Account\WishlistDetail;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = CustomerRepo::wishlists(current_customer());
        $data = [
            'wishlist' => WishlistDetail::collection($wishlists)->jsonSerialize(),
        ];

        return view('account/wishlist', $data);
    }

    public function add(Request $request): array
    {
        $productId = $request->get('product_id');
        CustomerRepo::addToWishlist(current_customer(), $productId);

        $wishlists = CustomerRepo::wishlists(current_customer());

        return json_success('加入收藏成功', $wishlists);
    }

    public function remove(Request $request): array
    {
        $productId = $request->product_id;
        CustomerRepo::removeFromWishlist(current_customer(), $productId);

        $wishlists = CustomerRepo::wishlists(current_customer());

        return json_success('移除收藏成功', $wishlists);
    }

}
