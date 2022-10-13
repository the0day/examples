<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        return OfferResource::collection(Offer::all());
    }

    public function show(Offer $offer)
    {
        return new OfferResource($offer);
    }
}
