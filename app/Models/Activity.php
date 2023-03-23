<?php

namespace App\Models;

use App\Enums\ActivityEvents;
use App\QueryBuilders\ActivityQueryBuilder;
use App\Scopes\VisibleToScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory;

    /** @var array<string, string> */
    protected $casts = [
        'id' => 'integer',
        'subject_id' => 'integer',
        'causer_id' => 'integer',
        'properties' => 'array',
        'event' => ActivityEvents::class,
    ];

    /** @param  Builder  $query */
    public function newEloquentBuilder($query): ActivityQueryBuilder
    {
        return new ActivityQueryBuilder($query);
    }

    public static function getSubjectTypes(): Collection
    {
        return self::groupBy('subject_type')->get('subject_type')->pluck('subject_type');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new VisibleToScope('causer_id'));
    }
}
