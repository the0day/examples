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
        Schema::create('user_authorizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->userId();
            $table->ipAddress('ip')->comment('IP which was used for login');
            $table->boolean('is_successful')->default(false)->comment('True if sign in was successful');
            $table->createdAt();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_log_history');
    }
};

