<?php
/**
 * Attribute.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-01-03 20:22:18
 * @modified   2023-01-03 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Base
{
    use HasFactory;

    protected $fillable = ['attribute_group_id', 'sort_order'];

    public function attributeGroup(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function description()
    {
        return $this->hasOne(AttributeDescription::class)->where('locale', locale());
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(AttributeDescription::class);
    }
}
