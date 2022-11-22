<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self assigning() - 0: воркер не назначен
 * @method static self assigned() - 1: воркер назначен
 * @method static self rejected() - 2: произошел reject (web3->send завершился ошибкой):
 * @method static self accepted() - 3: транзакция принята, получен хеш, ожидание включения в блок
 * @method static self executedFail() 4: транзакция включена в блок, но был revert (ошибка при исполнении алгоритма)
 * @method static self executed() 5: транзакция включена в блок с успешным статусом и достаточным кол-вом подтверждений
 * @method static self receipt() - 6: получили transaction hash, получаем receipt
 */
final class TaskStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'assigning'    => 0,
            'assigned'     => 1,
            'rejected'     => 2,
            'accepted'     => 3,
            'executedFail' => 4,
            'executed'     => 5,
            'receipt'      => 6
        ];
    }
}
