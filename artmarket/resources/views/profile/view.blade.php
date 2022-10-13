<?php

use App\Models\User;

/** @var User $user */

$profileDetails = [];
if ($user->profile->country) {
    $profileDetails[__('user.field.profile.country')] = $user->profile->country;
}
if ($user->profile->city) {
    $profileDetails[__('user.field.profile.city')] = $user->profile->city;
}
if ($user->profile->languages) {
    $profileDetails[__('user.field.profile.languages')] = implode(",", $user->profile->languages);
}
?>

<x-app-layout>
    <section class="py-10">
        <div class="container mx-auto">
            <div class="flex flex-wrap -mx-4 mb-24">
                <div class="w-full md:w-1/4">
                    <div class="px-4 py-4 mb-5 lg:border lg:border-gray-200">
                        <x-user.avatar :src="$user->profile->getAvatarSrc()" size="large" class="mx-auto"/>
                        <div class="text-lg leading-6 font-medium space-y-1 py-2">
                            <h3 class="text-center">{{$user->profile->firstname}} {{$user->profile->lastname}}</h3>
                            <x-reviews-line max="5" current="3" rating="4.32" total="143" class="justify-center"/>
                        </div>
                        <x-button class="w-full">@lang('user.text.contact')</x-button>

                        <div class="border-bottom">
                            <x-display.list :items="$profileDetails" item-class="py-2 sm:py-3"/>
                        </div>
                    </div>

                    @if($user->profile->about)
                        <div class="px-4 py-4 mb-8 md:mb-0 lg:border lg:border-gray-200">
                            <h3 class="text-gray-900 font-bold truncate">@lang('user.text.description')</h3>
                            <p>{{ $user->profile->about }}</p>
                        </div>
                    @endif
                </div>
                <div class="w-full md:w-3/4 px-8 mb-8 md:mb-0">
                    <h3 class="text-2xl font-bold pb-3">@lang('user.text.portfolio')</h3>
                    <x-offer.grid :items="$offers"/>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
