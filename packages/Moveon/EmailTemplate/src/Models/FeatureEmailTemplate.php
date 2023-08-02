<?php

namespace Moveon\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureEmailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'feature_email_templates';
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

    protected $casts = [
        'design' => 'array'
    ];

}
