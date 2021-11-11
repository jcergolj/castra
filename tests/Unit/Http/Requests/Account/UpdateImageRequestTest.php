<?php

namespace Tests\Unit\Http\Requests\Account;

use App\Http\Requests\Account\UpdateImageRequest;
use Illuminate\Http\UploadedFile;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Account\UpdateImageRequest */
class UpdateImageRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_update_image_rules_pass()
    {
        $this->createFormRequest(UpdateImageRequest::class)
            ->by(create_user())
            ->validate([
                'profile_image' => UploadedFile::fake()->image('image.jpg', 1000, 1000),
            ])->assertPasses();
    }

    /** @test */
    public function profile_image_is_required()
    {
        $this->createFormRequest(UpdateImageRequest::class)
            ->by(create_user())
            ->validate(['profile_image' => ''])
            ->assertFails(['profile_image' => 'required']);
    }

    /** @test */
    public function profile_image_must_be_image_type()
    {
        $this->createFormRequest(UpdateImageRequest::class)
            ->by(create_user())
            ->validate([
                'profile_image' => UploadedFile::fake()->create('document.pdf', 100),
            ])->assertFails(['profile_image' => 'image']);
    }

    /** @test */
    public function profile_imag_has_min_width_and_height()
    {
        $this->createFormRequest(UpdateImageRequest::class)
            ->by(create_user())
            ->validate([
                'profile_image' => UploadedFile::fake()->image('image.jpg', 10, 1000),
            ])->assertFails(['profile_image' => 'dimension']);

        $this->createFormRequest(UpdateImageRequest::class)
            ->by(create_user())
            ->validate([
                'profile_image' => UploadedFile::fake()->image('image.jpg', 1000, 10),
            ])->assertFails(['profile_image' => 'dimensions:min_width=100,min_height=100']);
    }
}
