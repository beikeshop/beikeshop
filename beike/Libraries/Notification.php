<?php
/**
 * Notification.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-07 10:52:57
 * @modified   2022-07-07 10:52:57
 */

namespace Beike\Libraries;


class Notification
{
    /**
     * @param string $code
     * @param string $message
     * @param string $type  email|telephone
     * @return bool
     */
    public static function verifyCode(string $code, string $message, string $type): bool
    {

        return true;
    }
}
