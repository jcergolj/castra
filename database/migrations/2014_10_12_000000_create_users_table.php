<?php

use App\Enums\UserRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @return void */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->string('role')->default(UserRoles::member->value);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /** @return void */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
