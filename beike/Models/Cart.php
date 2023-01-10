<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Base
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'session_id', 'shipping_address_id', 'guest_shipping_address', 'shipping_method_code', 'payment_address_id', 'guest_payment_address', 'payment_method_code', 'extra',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }

    public function paymentAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'payment_address_id', 'id');
    }
}
