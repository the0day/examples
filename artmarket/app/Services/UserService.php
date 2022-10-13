<?php

namespace App\Services;

use App\Http\Requests\Account\PersonalRequest;
use App\Models\Glossary\City;
use App\Models\Glossary\Country;
use App\Models\User;
use Cknow\Money\Money;

class UserService
{
    /**
     * Обновление личных данных профиля
     *
     * @param User $user
     * @param PersonalRequest $request
     * @return bool
     */
    public static function updateProfile(User $user, PersonalRequest $request)
    {
        $profile = $user->profile;
        $user->email = $request->get('email');

        $profile->lastname = $request->get('lastname');
        $profile->firstname = $request->get('firstname');
        $profile->phone = $request->get('phone');

        if ($countryId = $request->get('country_id')) {
            $country = Country::find($countryId);
            $profile->country_id = $country->id;
            $profile->country = $country->name;
        }

        if ($countryId && $cityId = $request->get('city_id')) {
            $city = City::find($cityId);
            $profile->city_id = $city->id;
            $profile->city = $city->name;
        }

        return $user->save() && $profile->save();
    }

    /**
     * Увеличить баланс пользователя
     *
     * @param User $user
     * @param Money $money
     */
    public function addCredit(User $user, Money $money): void
    {
        $user->credit = $user->credit->add($money);
        $user->save();
    }

    /**
     * Уменьшить баланс пользователя
     *
     * @param User $user
     * @param Money $money
     */
    public function subtractCredit(User $user, Money $money): void
    {
        $user->credit = $user->credit->subtract($money);
        $user->save();
    }
}
