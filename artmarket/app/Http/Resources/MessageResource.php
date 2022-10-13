<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'body'         => $this->body,
            'order_id'     => $this->order_id,
            'images'       => $this->getImages(),
            'published_at' => $this->created_at->diffForHumans()
        ];
    }
}
