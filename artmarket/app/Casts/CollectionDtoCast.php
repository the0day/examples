<?php

namespace App\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use Nette\Utils\Json;

class CollectionDtoCast implements CastsAttributes
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
            return new $this->class;
        }

        return new $this->class(Json::decode($value));
    }

    /**
     * Prepare the given value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return;
        }

        if (is_array($value)) {
            $value = new $this->class($value);
        }

        if (!$value instanceof $this->class) {
            throw new Exception("Value must be of type [$this->class], array, or null");
        }

        if ($value instanceof Collection) {
            $value = $value->flatten();
        }

        return Json::encode($value);
    }
}
