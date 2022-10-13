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
        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->char('iso3', 3)->nullable();
            $table->char('iso2', 2)->nullable();
            $table->string('phone_code')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedSmallInteger('country_id');
            $table->char('country_code', 2);
            $table->string('state_code')->nullable();

            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('state_id');
            $table->char('state_code', 5);
            $table->unsignedSmallInteger('country_id');
            $table->char('country_code', 3);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
};
