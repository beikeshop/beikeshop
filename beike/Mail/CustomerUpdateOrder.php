<?php
/**
 * CustomerUpdateOrder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-22 14:09:37
 * @modified   2022-12-22 14:09:37
 */

namespace Beike\Mail;

use Beike\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class CustomerUpdateOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Order $order;

    private string $fromCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $fromCode)
    {
        $this->order    = $order;
        $this->fromCode = $fromCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderLocale = $this->order->locale;
        App::setLocale($orderLocale);

        return $this->view('mails.order_update', ['order' => $this->order, 'from_code' => $this->fromCode]);
    }
}
