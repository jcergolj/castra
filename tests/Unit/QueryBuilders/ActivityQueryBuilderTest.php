<?php

namespace Tests\Unit\QueryBuilders;

use App\Enums\ActivityEvents;
use App\Models\Activity;
use Tests\TestCase;

/** @see \App\QueryBuilders\ActivityQueryBuilder */
/**
 *  I have a dilemma here. Arguably this test in a feature one not unit one. We could test it in isolation but the value of such test would be negligible.
 *  Testing UserQueryBuilder from the prospective of user model makes more sense and bring much more value.
 */
class ActivityQueryBuilderTest extends TestCase
{
    /** @test */
    public function filter_by_event()
    {
        $activityEmailUpdated = Activity::factory()->create(['event' => ActivityEvents::EmailUpdatedByUser]);
        $activityEventNull = Activity::factory()->create(['event' => null]);
        $activityOtherEvent = Activity::factory()->create(['event' => ActivityEvents::Deleted]);

        $activities = Activity::event(ActivityEvents::EmailUpdatedByUser->value)->get();

        $this->assertCount(1, $activities);
        $this->assertTrue($activities->contains($activityEmailUpdated));
        $this->assertFalse($activities->contains($activityEventNull));
        $this->assertFalse($activities->contains($activityOtherEvent));
    }

    /** @test */
    public function filter_by_causer_id()
    {
        $causer = create_member();

        $activity1 = Activity::factory()->create(['causer_id' => $causer->id]);
        $activity2 = Activity::factory()->create(['causer_id' => create_member()->id]);
        $activity3 = Activity::factory()->create(['causer_id' => create_member()->id]);

        $activities = Activity::causerId($causer->id)->get();

        $this->assertCount(1, $activities);
        $this->assertTrue($activities->contains($activity1));
        $this->assertFalse($activities->contains($activity2));
        $this->assertFalse($activities->contains($activity3));
    }

    /** @test */
    public function filter_by_subject_type()
    {
        $activity1 = Activity::factory()->create(['subject_type' => 'User']);
        $activity2 = Activity::factory()->create(['subject_type' => 'Activity']);
        $activity3 = Activity::factory()->create(['subject_type' => 'Team']);

        $activities = Activity::subjectType('User')->get();

        $this->assertCount(1, $activities);
        $this->assertTrue($activities->contains($activity1));
        $this->assertFalse($activities->contains($activity2));
        $this->assertFalse($activities->contains($activity3));
    }

    /** @test */
    public function filter_by_log_name()
    {
        $activity1 = Activity::factory()->create(['log_name' => 'default']);
        $activity2 = Activity::factory()->create(['log_name' => 'security']);
        $activity3 = Activity::factory()->create(['log_name' => 'others']);

        $activities = Activity::logName('default')->get();

        $this->assertCount(1, $activities);
        $this->assertTrue($activities->contains($activity1));
        $this->assertFalse($activities->contains($activity2));
        $this->assertFalse($activities->contains($activity3));
    }
}
