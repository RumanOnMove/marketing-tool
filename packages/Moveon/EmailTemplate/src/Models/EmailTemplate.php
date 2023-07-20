<?php

namespace Moveon\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'email_templates';
    protected $fillable = [
        'name',
        'subject',
        'type',
        'placeholders',
        'content',
        'status'
    ];

    protected $casts = [
        'placeholders' => 'array'
    ];

}
