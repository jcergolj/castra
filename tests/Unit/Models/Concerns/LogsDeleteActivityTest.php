<?php

namespace Tests\Unit\Models\Concerns;

use App\Enums\ActivityEvents;
use App\Models\Activity;
use App\Models\Concerns\LogsDeleteActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** @see \App\Models\Concerns\LogsDeleteActivity */
class LogsDeleteActivityTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    /** @test */
    public function deleted_model_event_is_logged()
    {
        $request = new Request();

        $request->setRouteResolver(function () use ($request) {
            return (new Route('GET', 'restore', [
                'as' => 'restore',
            ]))->bind($request);
        });

        config(['activitylog.subject_returns_soft_deleted_models' => true]);

        $this->assertCount(0, Activity::get());

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
        });

        $team = Team::create();

        $team->delete();

        $this->assertCount(1, Activity::get());

        $activity = Activity::first();

        $this->assertSame(ActivityEvents::deleted, $activity->event);
        $this->assertTrue($activity->causer->is($admin));
        $this->assertTrue($activity->subject->is($team));

        $this->assertSame(['restore_url' => route('restore')], $activity->properties);
    }
}

class Team extends Model
{
    use LogsDeleteActivity;

    public $timestamps = false;

    /** @return string */
    public function restoreRouteName()
    {
        return 'restore';
    }
}
