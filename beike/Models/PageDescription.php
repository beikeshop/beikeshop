<?php
/**
 * Page.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-08 15:10:57
 * @modified   2022-08-08 15:10:57
 */

namespace Beike\Models;

class PageDescription extends Base
{
    protected $fillable = [
        'page_id', 'locale', 'title', 'content', 'meta_title', 'meta_description', 'meta_keyword'
    ];
}
