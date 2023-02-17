<?php

namespace App\QueryBuilders;

use App\Enums\ActivityEvents;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @extends Builder<TModelClass>
 */
class ActivityQueryBuilder extends Builder
{
    use Filterable;

    public function event(string|null $event = null): Builder
    {
        $this->when($event, function ($query, $event) {
            $query->where('event', ActivityEvents::from($event));
        });

        return $this;
    }

    public function causerId(string|null $causer = null): Builder
    {
        $this->when($causer, function ($query, $causer) {
            $query->where('causer_id', $causer);
        });

        return $this;
    }

    public function subjectType(string|null $subjectType = null): Builder
    {
        $this->when($subjectType, function ($query, $subjectType) {
            $query->where('subject_type', $subjectType);
        });

        return $this;
    }

    public function logName(string|null $logName = null): Builder
    {
        $this->when($logName, function ($query, $logName) {
            $query->where('log_name', $logName);
        });

        return $this;
    }
}
