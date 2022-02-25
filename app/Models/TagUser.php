<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperTagUser
 */
class TagUser extends Pivot
{
    protected $table = 'tag_user';

    protected $fillable = [
        'user_id',
        'tag_id',
        'progress',
        'assigned_at',
        'data',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

}
