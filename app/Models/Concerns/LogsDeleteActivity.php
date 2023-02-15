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

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * @return void
     */
    public function tapActivity(Activity $activity)
    {
        if (! in_array($activity->event, static::$recordEvents)) {
            return;
        }

        $activity->event = ActivityEvents::Deleted->value;
        $activity->properties = ['subject' => $activity->subject];
    }
}
