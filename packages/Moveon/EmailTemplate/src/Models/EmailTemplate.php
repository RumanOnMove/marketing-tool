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
        'design',
        'html',
        'status'
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';

    const STATUS = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVE,
    ];

    const SORT_BY_NAME = 'name';
    const SORT_BY_DATE = 'date';

    const SORT_BY = [
        self::SORT_BY_NAME,
        self::SORT_BY_DATE,
    ];

    protected $casts = [
        'design' => 'array'
    ];

}
