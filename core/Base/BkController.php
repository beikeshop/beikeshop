<?php
/**
 * BkController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.beike.shop
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-03-17 16:41:53
 * @modified   2022-03-17 16:41:53
 */

use App\Http\Controllers\Controller;

class BkController extends Controller
{
    public function __construct()
    {
        $this->app = app();
    }

    public function hook($name)
    {
        $this->setParamsAlias();
        if (method_exists($this, 'beforeMain')) {
            $this->beforeMain($name);
        }
        if (method_exists($this, 'prefixClearCache')) {
            $this->prefixClearCache($name);
        }
    }

    public function registerProviders()
    {
        if (!empty($this->providers)) {
            foreach ($this->providers as $val) {
                $this->app->register($val);
            }
        }
    }

    private function setParamsAlias()
    {
        $this->saveParamsAlias = [];
        foreach ($this->paramsAlias as $k => $v) {
            $p = $this->inPut($v);
            if (!empty($p)) {
                $this->saveParamsAlias[$k] = $p;
            }
        }
    }
}
