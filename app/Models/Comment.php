<?php

namespace App\Models;

use App\Contract\FlushesCache;
use Database\Factories\CommentFactory;
use Dipesh79\LaravelHelpers\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory, FlushesCache, Filterable;

    protected $fillable = [
        'body',
        'task_id',
        'user_id',
    ];

    protected array $filterable = [
        'body',
        'user.name',
        'task.title'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
