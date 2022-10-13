<?php

namespace App\DTO;

use App\Casts\CollectionDtoCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

abstract class CastableCollectionData extends DataTransferObject implements Castable
{
    public static function castUsing(array $arguments): CollectionDtoCast
    {
        return new CollectionDtoCast(static::class);
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return Json::encode($this->toArray());
    }

    /**
     * @throws UnknownProperties
     * @throws JsonException
     */
    public static function fromJson($json): static
    {
        return new static(Json::decode($json, true));
    }
}
