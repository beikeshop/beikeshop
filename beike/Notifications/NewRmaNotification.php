<?php
/**
 * RegistrationNotification.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-12-22 14:09:37
 * @modified   2022-12-22 14:09:37
 */

namespace Beike\Notifications;

use Beike\Mail\CustomerNewRma;
use Beike\Models\Rma;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRmaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Rma $rma;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Rma $rma)
    {
        $this->rma = $rma;
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
        $mailAlert = system_setting('base.mail_customer') ?? [];

        if ($mailEngine && in_array('return', $mailAlert)) {
            $drivers[] = 'mail';
        }

        return $drivers;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return CustomerNewRma
     */
    public function toMail($notifiable)
    {
        return (new CustomerNewRma($this->rma))
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
     * @return Rma[]
     */
    public function toDatabase()
    {
        return [
            'rma' => $this->rma,
        ];
    }
}
