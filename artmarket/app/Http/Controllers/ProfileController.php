<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    /**
     * Публичный профиль
     * @param User $user
     * @return View
     */
    public function view(User $user)
    {
        return view('profile.view')
            ->with('user', $user)
            ->with('offers', $user->offers()->get());
    }
}
