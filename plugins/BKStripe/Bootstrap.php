<?php
/**
 * bootstrap.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-20 15:35:59
 * @modified   2022-07-20 15:35:59
 */

namespace Plugin\BKStripe;

use TorMorten\Eventy\Facades\Eventy;

class Bootstrap
{
    public function boot()
    {
        // dump(__CLASS__, __METHOD__, __FUNCTION__);
        Eventy::addFilter('header.categories', function($data) {
            // dump($data);
            return $data;
        }, 20, 1);
    }
}
