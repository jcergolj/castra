<?php

namespace App\QueryBuilders;

use App\Enums\ActivityEvents;
use Illuminate\Database\Eloquent\Builder;

class ActivityQueryBuilder extends Builder
{
    use Filterable;

    /**
     * Filter by event.
     *
     * @param  string|null  $event
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function event($event = null)
    {
        $this->when($event, function ($query, $event) {
            $query->where('event', ActivityEvents::from($event));
        });

        return $this;
    }

    /**
     * Filter by causer.
     *
     * @param  string|null  $causer
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function causerId($causer = null)
    {
        $this->when($causer, function ($query, $causer) {
            $query->where('causer_id', $causer);
        });

        return $this;
    }

    /**
     * Filter by subject type.
     *
     * @param  string|null  $subjectType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function subjectType($subjectType = null)
    {
        $this->when($subjectType, function ($query, $subjectType) {
            $query->where('subject_type', $subjectType);
        });

        return $this;
    }

    /**
     * Filter by log name.
     *
     * @param  string|null  $logName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function logName($logName = null)
    {
        $this->when($logName, function ($query, $logName) {
            $query->where('log_name', $logName);
        });

        return $this;
    }
}
