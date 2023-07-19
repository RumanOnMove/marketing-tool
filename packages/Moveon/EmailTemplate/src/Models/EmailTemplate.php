<?php

namespace Moveon\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
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
