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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->userId();
            $table->string("icon")->nullable()->comment('Notification icon');
            $table->string("message")->nullable()->comment('Notification body');
            $table->string("channel")->nullable()->comment('Notification channel (news, chat, new order)');
            $table->jsonb("params")->nullable()->comment('Notification channel (news, chat, new order)');
            $table->string('model_type')->nullable()->comment('Related model (for generation link). Example: App\Models\User');
            $table->string('model_id')->nullable()->comment('Related model ID. Example: 1');
            $table->boolean('has_read')->default(false)->comment('Set to true after read a notification');
            $table->timestamp('read_at',
                0)->nullable()->comment('When it sets is_read to true, it also sets readed time');
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
        Schema::dropIfExists('user_notifications');
    }
};
