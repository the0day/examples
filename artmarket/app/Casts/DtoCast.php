<?php

namespace App\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Nette\Utils\Json;

class DtoCast implements CastsAttributes
{
    /** @var string The DataTransferObject class to cast to */
    public function __construct(protected string $class)
    {

    }

    /**
     * Cast the stored value to the configured DataTransferObject.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return;
        }

        return $this->class::fromJson($value, Json::decode($value));
    }

    /**
     * Prepare the given value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            $value = new $this->class($value);
        }

        if (!$value instanceof $this->class) {
            throw new Exception("Value must be of type [$this->class], array, or null");
        }

        return Json::encode($value);
    }
}
