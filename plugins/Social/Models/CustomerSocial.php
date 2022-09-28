<?php
/**
 * CustomerSocial.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-10-13 10:35:44
 * @modified   2022-10-13 10:35:44
 */

namespace Plugin\Social\Models;

use Beike\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSocial extends Model
{
    public $table = 'customer_socials';

    public $fillable = [
        'customer_id', 'provider', 'user_id', 'union_id', 'access_token', 'extra'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
