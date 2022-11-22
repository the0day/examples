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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->unsignedBigInteger('operator_id');
            $table->tinyInteger('сhain_id');
            $table->integer('block_id')->comment('ID блока');
            $table->string('tx_to')->comment('Получатель транзакции');
            $table->integer('tx_value')->comment('Количество отправляемого эфира');
            $table->text('tx_data')->comment('данные (комментарий) к транзакции');
            $table->string('tx_hash')->nullable();
            $table->string('tx_temp_hash')->nullable();
            $table->tinyInteger('status')->comment('0 - воркер не назначен, 1 - воркер назначен, 2 - произошел reject (web3->send завершился ошибкой), 3 - транзакция принята, получен хеш, ожидание включения в блок, 4 - транзакция включена в блок, но был revert (ошибка при исполнении алгоритма), 5 - транзакция включена в блок с успешным статусом и достаточным кол-вом подтверждений');
            $table->dateTime('registered_at')->comment('Время регистрации задания');
            $table->dateTime('executed_at')->nullable()->nullable()->comment('время получения результата (реджект, реверт, успех)');
            $table->text('response')->nullable()->comment('данные, возникшие при исполнении транзакции');
            $table->dateTime('post_at')->nullable()->comment('время отложенного исполнения (задание назначается воркеру только после указанного времени)');
            $table->string('post_at_node')->nullable();
            $table->tinyInteger('role_id')->comment('какая роль должна быть у свободного воркера для назначения такой задачи');
            $table->smallInteger('priority')->comment('вес, по которому сортируются задания для выполнения воркерами');
            $table->string('uuid')->comment('UUID транзакции - уникальный символьный ключ транзакции, по которому возможно обращение извн');

            $table->foreign('worker_id')->references('id')->on('workers');
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
        Schema::dropIfExists('tasks');
    }
};
