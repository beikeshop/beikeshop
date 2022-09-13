<?php
/**
 * TaxRule.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-21 18:39:48
 * @modified   2022-07-21 18:39:48
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends Base
{
    protected $fillable = ['tax_class_id', 'tax_rate_id', 'based', 'priority'];

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }
}
