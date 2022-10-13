<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\PersonalRequest;
use App\Services\UserService;
use Auth;

class PersonalController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('account.edit')->with('user', $user);
    }

    public function update(PersonalRequest $request)
    {
        UserService::updateProfile(Auth::user(), $request);
        return redirect(route("account.personal"))->with('success', __('app.saved'));
    }
}
