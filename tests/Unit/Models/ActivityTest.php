<?php

namespace Tests\Unit\Models;

use App\Enums\ActivityEvents;
use App\Models\Activity;
use App\QueryBuilders\ActivityQueryBuilder;
use App\Scopes\VisibleToScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/** @see \App\Models\Activity */
class ActivityTest extends TestCase
{
    /** @test */
    public function assert_model_uses_has_factory_trait()
    {
        $this->assertContains(HasFactory::class, class_uses(Activity::class));
    }

    /** @test */
    public function assert_id_is_cast()
    {
        $activity = Activity::factory()->create();
        $this->assertSame('integer', $activity->getCasts()['id']);
    }

    /** @test */
    public function assert_causer_id_is_cast()
    {
        $activity = Activity::factory()->create();
        $this->assertSame('integer', $activity->getCasts()['causer_id']);
    }

    /** @test */
    public function assert_subject_id_is_cast()
    {
        $activity = Activity::factory()->create();
        $this->assertSame('integer', $activity->getCasts()['subject_id']);
    }

    /** @test */
    public function assert_properties_is_cast()
    {
        $activity = Activity::factory()->create();
        $this->assertSame('array', $activity->getCasts()['properties']);
    }

    /** @test */
    public function assert_event_is_cast()
    {
        $activity = Activity::factory()->create();
        $this->assertNotInstanceOf(ActivityEvents::class, $activity->getCasts()['event']);
    }

    /** @test */
    public function new_eloquent_builder()
    {
        $query = $this->app->make(Builder::class);
        $activity = Activity::factory()->create();
        $this->assertInstanceOf(ActivityQueryBuilder::class, $activity->newEloquentBuilder($query));
    }

    /** @test */
    public function visible_to_global_scope_is_applied()
    {
        $activity = Activity::factory()->create();
        $this->assertInstanceOf(
            VisibleToScope::class,
            $activity->getGlobalScopes()['App\Scopes\VisibleToScope']
        );
    }

    /** @test */
    public function get_subject_types()
    {
        Activity::factory()->create(['subject_type' => 'User']);
        Activity::factory()->create(['subject_type' => 'User']);
        Activity::factory()->create(['subject_type' => 'Member']);

        $subjectTypes = Activity::getSubjectTypes();

        $this->count(2, $subjectTypes);

        $this->assertSame(['Member', 'User'], $subjectTypes->toArray());
    }
}
