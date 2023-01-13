<?php
/**
 * UpdateOrderNotification.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-22 14:09:37
 * @modified   2022-12-22 14:09:37
 */

namespace Beike\Notifications;

use Beike\Mail\CustomerUpdateOrder;
use Beike\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UpdateOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Order $order;

    private string $fromCode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, $fromCode)
    {
        $this->order    = $order;
        $this->fromCode = $fromCode;
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
     * @return CustomerUpdateOrder
     */
    public function toMail($notifiable)
    {
        return (new CustomerUpdateOrder($this->order, $this->fromCode))
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
     * @return Order[]
     */
    public function toDatabase()
    {
        return [
            'order' => $this->order,
        ];
    }
}
