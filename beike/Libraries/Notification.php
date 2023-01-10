<?php
/**
 * Notification.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-07 10:52:57
 * @modified   2022-07-07 10:52:57
 */

namespace Beike\Libraries;

class Notification
{
    /**
     * @param string $code
     * @param string $message
     * @param string $type    email|telephone
     * @return bool
     */
    public static function verifyCode(string $code, string $message, string $type): bool
    {

        return true;
    }
}
