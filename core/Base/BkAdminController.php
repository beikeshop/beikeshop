<?php

/**
 * BkAdminController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-03-18 18:37:59
 * @modified   2022-03-18 18:37:59
 */

class BkAdminController extends BkController
{
    protected function checkRequestPermissions($userRepo)
    {
        return $this->user->isAdmin();
    }
}
