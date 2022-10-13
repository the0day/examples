<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->userId()->unique();
            $table->string('firstname')->nullable()->comment('First name of user');
            $table->string('lastname')->nullable()->comment('Last name of user');
            $table->string('avatar')->nullable()->comment('User avatar path');
            $table->char('gender', 1)->nullable()->comment('M - Male, F - Female');
            $table->date('birthday')->nullable()->comment('Birthday of user');
            $table->string('about')->nullable()->comment('Additional information about user');
            $table->string('phone')->nullable()->comment('Phone number of user');
            $table->string('tagline')->nullable()->comment('Slogan is using in advertising');
            $table->unsignedInteger('country_id')->nullable()->comment('Country ID');
            $table->unsignedInteger('city_id')->nullable()->comment('City ID');
            $table->string('country')->nullable()->comment('Country');
            $table->string('city')->nullable()->comment('City');
            $table->jsonb('social')->nullable()->comment('Links for social networks (FB, Twitch, YouTube)');
            $table->jsonb('languages')->nullable()->comment('Language skills');
            $table->updatedAt();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
