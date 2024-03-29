<?php

namespace Tests\Unit\Models\Concerns;

use App\Enums\ActivityEvents;
use App\Models\Activity;
use App\Models\Concerns\LogsDeleteActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** @see \App\Models\Concerns\LogsDeleteActivity */
class LogsDeleteActivityTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function deleted_model_event_is_logged()
    {
        $this->actingAs($admin = create_admin());

        config(['activitylog.subject_returns_soft_deleted_models' => true]);

        $this->assertCount(0, Activity::get());

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
        });

        $team = Team::create();

        $teamId = $team->id;
        $team->delete();

        $this->assertCount(1, Activity::get());

        $activity = Activity::first();

        $this->assertSame(ActivityEvents::Deleted, $activity->event);

        $this->assertTrue($activity->causer->is($admin));

        $this->assertSame($teamId, $activity->subject_id);
        $this->assertSame(Team::class, $activity->subject_type);
    }
}

class Team extends Model
{
    use LogsDeleteActivity;

    public $timestamps = false;
}
