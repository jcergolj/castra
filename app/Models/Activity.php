<?php

namespace App\Models;

use App\QueryBuilders\ActivityQueryBuilder;
use App\Scopes\VisibleToScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory;

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'subject_id' => 'integer',
        'causer_id' => 'integer',
        'properties' => 'array',
    ];

    /** @return void */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibleToScope('causer_id'));
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new ActivityQueryBuilder($query);
    }

    /** @return \Illuminate\Database\Eloquent\Collection */
    public static function getSubjectTypes()
    {
        return self::groupBy('subject_type')->get('subject_type')->pluck('subject_type');
    }
}
