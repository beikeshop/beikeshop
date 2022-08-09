<?php
/**
 * Page.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-08 15:10:57
 * @modified   2022-08-08 15:10:57
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Page extends Base
{
    protected $fillable = [
        'position', 'active'
    ];

    public function description(): HasOne
    {
        return $this->hasOne(PageDescription::class)->where('locale', locale());
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(PageDescription::class);
    }
}
