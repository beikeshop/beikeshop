<?php
/**
 * UserForgotten.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-12-23 14:09:37
 * @modified   2022-12-23 14:09:37
 */

namespace Beike\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserForgotten extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private string $code;

    private string $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $code, string $email)
    {
        $this->code  = $code;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.forgotten', ['code' => $this->code, 'is_admin' => true, 'email' => $this->email]);
    }
}
