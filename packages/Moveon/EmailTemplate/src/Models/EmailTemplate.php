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

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETE = 'delete';

    const STATUS = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVE,
        self::STATUS_DELETE
    ];

    protected $casts = [
        'placeholders' => 'array'
    ];

}
