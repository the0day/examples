<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TaskResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'address' => $this->address,
            'balance' => 0,
            'role_id' => $this->role_id,
            'status'  => $this->status
        ];
    }
}
