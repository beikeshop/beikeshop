<?php
/**
 * TaxRule.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 18:39:48
 * @modified   2022-07-21 18:39:48
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends Base
{
    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }
}
