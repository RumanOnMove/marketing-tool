<?php

namespace Moveon\Image\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'name',
        'type',
        'link'
    ];
}
