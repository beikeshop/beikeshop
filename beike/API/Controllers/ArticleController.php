<?php
/**
 * ArticleController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-21 18:02:22
 * @modified   2023-08-21 18:02:22
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\PageRepo;
use Beike\Shop\Http\Resources\PageDetail;

class ArticleController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        try {
            return PageDetail::collection(PageRepo::getCategoryPages());
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
