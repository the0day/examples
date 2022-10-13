<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CollectionData extends Collection
{
    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->all());
    }
}
