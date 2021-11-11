<?php

namespace Tests\Unit\Http\Requests\Admin;

use App\Http\Requests\Admin\StoreDeletedUserRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Admin\StoreDeletedUserRequest */
class StoreDeletedUserRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_update_email_rules_pass()
    {
        $admin = create_admin();
        $this->makeRequestWithAuth($admin);
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by($admin)
            ->validate([
                'ids' => [create_user()->id],
            ])
            ->assertPasses();
    }

    /** @test */
    public function admin_cannot_delete_himself()
    {
        $admin = create_admin();
        $this->makeRequestWithAuth($admin);
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by($admin)
            ->validate(['ids' => [$admin->id]])
            ->assertFails();
    }

    /** @test */
    public function ids_param_must_not_be_empty()
    {
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by(create_admin())
            ->validate(['ids' => []])
            ->assertFails();
    }

    /** @test */
    public function ids_param_must_be_array()
    {
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by(create_admin())
            ->validate(['ids' => 'string'])
            ->assertFails();
    }

    /** @test */
    public function in_array_only_ids_key_is_allowed()
    {
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by(create_admin())
            ->validate(['id' => [1]])
            ->assertFails();
    }

    /** @test */
    public function ids_param_must_be_present()
    {
        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by(create_admin())
            ->validate([])
            ->assertFails();
    }

    /** @test */
    public function users_must_exists()
    {
        $admin = create_admin();
        $this->makeRequestWithAuth($admin);

        $this->createFormRequest(StoreDeletedUserRequest::class)
            ->by($admin)
            ->validate(['ids' => [2]])
            ->assertFails();
    }
}
