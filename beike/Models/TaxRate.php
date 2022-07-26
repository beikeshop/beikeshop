<?php
/**
 * TaxRate.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 18:39:38
 * @modified   2022-07-21 18:39:38
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Base
{
    protected $fillable = ['title', 'description'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
