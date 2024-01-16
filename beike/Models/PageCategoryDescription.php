<?php
/**
 * PageCategoryDescription.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-09 10:33:08
 * @modified   2023-02-09 10:33:08
 */

namespace Beike\Models;

class PageCategoryDescription extends Base
{
    protected $table = 'page_category_descriptions';

    protected $fillable = [
        'page_category_id', 'locale', 'title', 'summary', 'meta_title', 'meta_description', 'meta_keywords',
    ];
}
