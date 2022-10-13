<?php

namespace App\Http\Controllers;

use App\Models\Offer;

class HomeController extends Controller
{
    public function index()
    {
        $offers = Offer::all();

        return view('home', [
            'offers' => $offers
        ]);
    }
}
