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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->userId();
            $table->offerId();
            $table->userId('seller_id', 'Seller ID');
            $table->string('status')->comment("1: Waiting for a payment
2: Waiting for accept from seller
3: Accepted by seller
4: Waiting for approve from client
5: Delivered\Approved
6: Declined by seller
7: Request cancellation from seller\client
8: Cancelled by client\seller\system");

            $table->unsignedInteger('job_cost')->default(0)->comment('Job Costs');
            $table->unsignedInteger('discount')->default(0)->comment('Discount');
            $table->unsignedInteger('admin_fee')->default(0)->comment('Admin Fee');
            $table->unsignedInteger('service_fee')->default(0)->comment('Service Fee');
            $table->unsignedInteger('upgrade_cost')->default(0)->comment('Upgrade Cost');
            $table->unsignedInteger('total_cost')->default(0)->comment('Total Cost');
            $table->string('currency', 3)->comment('Offer price currency');

            $table->json('coupons')->nullable()->comment('Applied coupons');
            $table->text('note_to_seller')->nullable()->comment('Note to seller from buyer');
            $table->text('note_to_buyer')->nullable()->comment('Note to buyer from seller');
            $table->jsonb('upgrades')->nullable()->comment('Upgrades');

            $table->timestamp('paid_at', 0)->nullable()->comment('Payment date');
            $table->timestamp('accepted_at', 0)->nullable()->comment('Order accepted date by seller');
            $table->timestamp('start_at', 0)->nullable()->comment('Order started date by seller');
            $table->timestamp('delivered_at', 0)->nullable()->comment('Order delivered to buyer');
            $table->timestamp('deadline_at', 0)->nullable()->comment('Order deadline');
            $table->timestamp('cancelled_at', 0)->nullable()->comment('Order cancelled date');
            /*$table->timestamp('last_message_at', 0)->nullable()->comment('Last message at');
            $table->timestamp('last_message_by', 0)->nullable()->comment('Last message at');*/
            $table->text('cancel_reason')->nullable()->comment('Cancel reason');
            $table->userId('cancelled_by_id', 'Who cancelled an order')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
