<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('mobile_number')->unique();
            $table->string('gender');
            $table->string('state');
            $table->string('city');
            $table->string('district');
            $table->string('school');
            $table->string('class');
            $table->string('school_board');
            $table->string('bio');
            $table->string('address');
            $table->string('facebook_link');
            $table->string('instagram_link');
            $table->string('interest');
            $table->string('status');
            $table->text('image')->nullable();
            $table->string('role');
            $table->string('has_child')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
