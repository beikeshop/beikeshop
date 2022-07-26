<?php
/**
 * TaxClass.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 18:39:28
 * @modified   2022-07-21 18:39:28
 */

namespace Beike\Models;

class TaxClass extends Base
{
    protected $fillable = ['title', 'description'];

    public function taxRates()
    {
        return $this->belongsToMany(TaxRate::class, 'tax_rules');
    }

    public function taxRules()
    {
        return $this->hasMany(TaxRule::class);
    }
}
