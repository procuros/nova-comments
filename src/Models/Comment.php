<?php

declare(strict_types=1);

namespace KirschbaumDevelopment\NovaComments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Support\Traits\UsesUuid;

class Comment extends Model
{
    use UsesUuid;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The "booting" method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(
            function ($comment): void {
                if (auth()->check()) {
                    $comment->commented_by = auth()->id();
                }
            }
        );
    }

    /**
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function commenter(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'commented_by');
    }
}
