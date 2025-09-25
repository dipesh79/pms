<?php

namespace App\Models;

use App\Contract\FlushesCache;
use Database\Factories\ProjectFactory;
use Dipesh79\LaravelHelpers\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, Filterable, FlushesCache;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'user_id'
    ];

    protected array $filterable = [
        'title',
        'description',
        'user.name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
