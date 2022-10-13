<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'id'          => $this->id,
            'url'         => '',
            'title'       => $this->getTitle(),
            'description' => $this->description,
            'price'       => $this->price,
            'currency'    => 'RUB',
            'is_promoted' => $this->is_promoted,
            'likes'       => $this->likes,
            'reviews'     => 33,
            'views'       => 1000,
            'reply_time'  => 300,
            'author'      => [
                'name'       => 'admin',
                'reviews'    => 100,
                'rating'     => 5,
                'avatar_src' => '/images/avatar.png'
            ],
            'breadcrumbs' => [
                [
                    'title'  => 'Home',
                    'url'    => '#',
                    'active' => false
                ],
                [
                    'title'  => 'Category',
                    'url'    => '#',
                    'active' => false
                ],
                [
                    'title'  => 'Sub category',
                    'url'    => '#',
                    'active' => true
                ],
            ],
            'images'      => [
                [
                    'src'   => 'https://picsum.photos/1024/480/?image=55',
                    'title' => 'Test'
                ],
                [
                    'src'   => 'https://picsum.photos/1024/480/?image=54',
                    'title' => 'Test'
                ]
            ]
        ];
    }
}
