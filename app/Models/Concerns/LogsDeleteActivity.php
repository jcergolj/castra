<?php

namespace App\Models\Concerns;

use App\Enums\ActivityEvents;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsDeleteActivity
{
    use LogsActivity;

    /** @var array */
    protected static $recordEvents = ['deleted'];

    /** @return string */
    abstract public function restoreRouteName();

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * @param  \Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function tapActivity(Activity $activity)
    {
        $activity->event = ActivityEvents::deleted;
        $activity->properties = ['restore_url' => route($this->restoreRouteName(), $activity->subject)];
    }
}
