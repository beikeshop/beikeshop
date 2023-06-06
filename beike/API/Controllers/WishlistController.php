<?php
/**
 * WishlistController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-16 10:29:13
 * @modified   2023-08-16 10:29:13
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\CustomerWishlist;
use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Resources\Account\WishlistDetail;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $customer = current_customer();
        if (empty($customer)) {
            return json_success(trans('common.get_success'));
        }

        $wishlists = CustomerRepo::wishlists($customer);

        return WishlistDetail::collection($wishlists)->jsonSerialize();
    }

    public function store(Request $request)
    {
        $productId = $request->get('product_id');
        $wishlist  = CustomerRepo::addToWishlist(current_customer(), $productId);

        return json_success(trans('shop/wishlist.add_wishlist_success'), $wishlist);
    }

    public function destroy(CustomerWishlist $wishlist)
    {
        $wishlist->delete();

        return json_success(trans('common.deleted_success'));
    }
}
