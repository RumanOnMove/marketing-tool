<?php

namespace Moveon\Image\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'name',
        'type',
        'link'
    ];

    # Categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_image');
    }
}
