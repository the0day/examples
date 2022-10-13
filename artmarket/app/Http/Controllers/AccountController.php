<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function edit()
    {
        return view('account.edit');
    }

    public function offers()
    {
        return view('account.offers.list');
    }

    public function index()
    {
        return view('account.profile');
    }

    public function purchases()
    {
        return view('account.purchases');
    }

    public function payments()
    {
        return view('account.payments');
    }

    public function reviews()
    {
        return view('account.reviews');
    }
}
