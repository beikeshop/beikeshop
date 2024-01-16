<?php
/**
 * UserController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 14:51:26
 * @modified   2023-04-20 14:51:26
 */

namespace Beike\AdminAPI\Controllers;

class UserController
{
    public function me()
    {
        return registry('admin_user');
    }
}
