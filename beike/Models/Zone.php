<?php
/**
 * Zone.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 15:22:18
 * @modified   2022-06-30 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Base
{
    use HasFactory;

    protected $fillable = ['country_id', 'name', 'code', 'sort_order', 'status'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
