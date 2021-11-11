<?php

namespace Tests\Unit\Scopes;

use App\Scopes\VisibleToScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** @see \App\Scopes\VisibleToScope */
class VisibleToScopeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
        });
    }

    /** @test */
    public function member_can_only_see_his_entities()
    {
        $member = create_member();

        $this->makeRequestWithAuth($member);

        $membersTeam = Team::create(['user_id' => $member->id]);
        Team::create(['user_id' => create_admin()->id]);

        $teams = Team::get();

        $this->assertCount(1, $teams);

        $this->assertTrue($teams->contains($membersTeam));
    }

    /** @test */
    public function admin_can_see_all_entities()
    {
        $admin = create_admin();

        $this->makeRequestWithAuth($admin);

        $adminsTeam = Team::create(['user_id' => $admin->id]);
        $members1Team = Team::create(['user_id' => create_member()->id]);
        $members2Team = Team::create(['user_id' => create_member()->id]);

        $teams = Team::get();

        $this->assertCount(3, $teams);

        $this->assertTrue($teams->contains($adminsTeam));
        $this->assertTrue($teams->contains($members1Team));
        $this->assertTrue($teams->contains($members2Team));
    }

    /** @test */
    public function guest_can_see_all_entities()
    {
        $adminsTeam = Team::create(['user_id' => create_member()->id]);
        $members1Team = Team::create(['user_id' => create_member()->id]);
        $members2Team = Team::create(['user_id' => create_member()->id]);

        $teams = Team::get();

        $this->assertCount(3, $teams);

        $this->assertTrue($teams->contains($adminsTeam));
        $this->assertTrue($teams->contains($members1Team));
        $this->assertTrue($teams->contains($members2Team));
    }
}

class Team extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VisibleToScope('user_id'));
    }
}
