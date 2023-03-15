<?php
/**
 * Brand.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-27 20:22:18
 * @modified   2022-07-27 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Base
{
    use HasFactory;

    protected $fillable = ['name', 'first', 'logo', 'sort_order', 'status'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getUrlAttribute()
    {
        $url     = shop_route('brands.show', ['id' => $this->id]);
        $filters = hook_filter('model.brand.url', ['url' => $url, 'brand' => $this]);

        return $filters['url'] ?? '';
    }
}
