<?php
/**
 * Page.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 15:10:57
 * @modified   2022-08-08 15:10:57
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Base
{
    protected $fillable = [
        'page_category_id', 'image', 'position', 'views', 'author', 'active',
    ];

    public function description(): HasOne
    {
        return $this->hasOne(PageDescription::class)->where('locale', locale());
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(PageDescription::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PageCategory::class, 'page_category_id', 'id');
    }

    public function pageProducts(): HasMany
    {
        return $this->hasMany(PageProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, PageProduct::class, 'page_id', 'product_id')->withTimestamps();
    }

    public function getUrlAttribute()
    {
        $url     = shop_route('pages.show', ['page' => $this]);
        $filters = hook_filter('model.page.url', ['url' => $url, 'page' => $this]);

        return $filters['url'] ?? '';
    }
}
