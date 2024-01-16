<?php
/**
 * AdminForgottenNotification.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-12-23 14:09:37
 * @modified   2022-12-23 14:09:37
 */

namespace Beike\Notifications;

use Beike\Mail\UserForgotten;
use Beike\Models\AdminUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminForgottenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private AdminUser $user;

    private string $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdminUser $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $drivers[]  = 'database';
        $mailEngine = system_setting('base.mail_engine');
        if ($mailEngine) {
            $drivers[] = 'mail';
        }

        return $drivers;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return UserForgotten
     */
    public function toMail($notifiable)
    {
        return (new UserForgotten($this->code, $this->user->email))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * 保存到 DB
     * @return AdminUser[]
     */
    public function toDatabase()
    {
        return [
            'user' => $this->user,
        ];
    }
}
