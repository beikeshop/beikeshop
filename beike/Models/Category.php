<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class);
    }

    public function description()
    {
        return $this->hasOne(CategoryDescription::class)->where('locale', locale());
    }

    public function paths()
    {
        return $this->hasMany(CategoryPath::class);
    }
}
