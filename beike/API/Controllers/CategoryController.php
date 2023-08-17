<?php
/**
 * CategoryController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-17 11:00:54
 * @modified   2023-08-17 11:00:54
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\CategoryRepo;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryRepo::getTwoLevelCategories();
    }
}