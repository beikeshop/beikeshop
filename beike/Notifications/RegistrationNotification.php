<?php
/**
 * RegistrationNotification.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-22 14:09:37
 * @modified   2022-12-22 14:09:37
 */

namespace Beike\Notifications;

use Beike\Mail\CustomerRegistration;
use Beike\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Customer $customer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
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
     * @return CustomerRegistration
     */
    public function toMail($notifiable)
    {
        return (new CustomerRegistration($this->customer))
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
     * @return Customer[]
     */
    public function toDatabase()
    {
        return [
            'customer' => $this->customer,
        ];
    }
}
