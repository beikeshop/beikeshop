<?php
/**
 * PageCategory.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-09 10:31:38
 * @modified   2023-02-09 10:31:38
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PageCategory extends Base
{
    protected $table = 'page_categories';

    protected $fillable = [
        'parent_id', 'position', 'active',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(PageCategoryDescription::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(PageCategoryDescription::class)->where('locale', locale());
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'page_category_id');
    }

    public function getUrlAttribute()
    {
        $url     = shop_route('page_categories.show', ['page_category' => $this]);
        $filters = hook_filter('model.page_category.url', ['url' => $url, 'page_category' => $this]);

        return $filters['url'] ?? '';
    }
}
