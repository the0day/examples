<x-account-layout label="settings"
                  :title="__('account.personal.title')"
                  :description="__('account.personal.description')">
    {{--<form action="{{route('account.personal.update')}}" method="POST">--}}

    <x-form>
        <div class="px-4">
            @bind($user->profile)
            <div class="mt-6 grid grid-cols-4 gap-y-2 gap-x-6 pb-5">

                <div class="col-span-4 sm:col-span-2">
                    <x-form-input name="firstname" id="firstname" label="{{__('user.field.profile.firstname')}}"/>
                </div>
                <div class="col-span-4 sm:col-span-2">
                    <x-form-input name="lastname" id="lastname" label="{{__('user.field.profile.lastname')}}"/>
                </div>

                <div class="col-span-4 sm:col-span-2">
                    @bind($user)
                    <x-form-input name="email" id="email" label="{{__('user.field.email')}}"/>
                    @endbind
                </div>
                <div class="col-span-4 sm:col-span-2">
                    <x-form-input name="phone" id="phone" label="{{__('user.field.profile.phone')}}"/>
                </div>

                <div class="col-span-4 sm:col-span-2">
                    <x-form-group label="{{__('user.field.profile.country')}}">
                        <livewire:country-select
                                name="country_id"
                                :placeholder="$user->profile->country"
                                :value="request('country_id')"
                                :searchable="true"/>
                    </x-form-group>
                </div>
                <div class="col-span-4 sm:col-span-2">
                    <x-form-group label="{{__('user.field.profile.city')}}">
                        <livewire:city-select
                                name="city_id"
                                :placeholder="$user->profile->city"
                                :depends-on="['country_id']"
                                :depends-on-values="['country_id' => request('country_id')]"
                                :searchable="true"/>
                    </x-form-group>
                </div>


            </div>

        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit"
                    class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                @lang('app.buttons.save')
            </button>
        </div>
        @endbind

    </x-form>
</x-account-layout>
