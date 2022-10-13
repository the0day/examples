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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Payment method system name');
            $table->jsonb('title')->comment('Payment method title (Translatable)');
            $table->integer('order')->nullable()->comment('Payment methods ordering');
            $table->boolean('active')->comment('Active payment method');
            $table->double('commission', 10, 2)->comment('Commission');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_method_id')->comment('Payment method ID');
            $table->userId();
            $table->orderId()->nullable();
            $table->offerId()->nullable();
            $table->string('status')->comment("");
            $table->createdAt();
            $table->unsignedInteger('amount')->comment('Amount');
            $table->string('currency', 3)->comment('Currency');

            $table->foreign('payment_method_id', 'payment_method_id')
                ->references('id')->on('payment_methods')
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
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payments');
    }
};
