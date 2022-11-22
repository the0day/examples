<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('operator_id')->comment('ID Оператора');
            $table->tinyInteger('status')->comment('(0 - свободен, 1 - отключен вручную, 2 - отключен из-за баланса ниже нормы, 3 - занят задачей)');
            $table->string('address')->comment('Адрес кошелька, который соответствует воркеру');
            $table->string('private_key')->comment('Приватный ключ кошелька для подписи транзакций');
            $table->double('balance')->nullable()->comment('Баланс кошелька');
            $table->tinyInteger('role_id')->comment('0 - обычный воркер, 1 - админ/менеджер, остальные коды - кастомные, настраиваются в конфиге, пока не используются');

            $table->foreign('operator_id')->references('id')->on('operators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
};
