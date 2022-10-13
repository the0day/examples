<?php

namespace App\DTO;

use Carbon\Carbon;
use Nette\Utils\Json;
use Spatie\DataTransferObject\DataTransferObject;

class ObjectData extends DataTransferObject
{
    public static function generateCarbonObject(?string $date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        return \Illuminate\Support\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'),
            $date);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function toJson(): string
    {
        return Json::encode($this->toArray());
    }
}
