<x-empty-modal id="auth" no-padding="true">
    <form method="POST" action="{{ route('login') }}" id="form-auth">
        @csrf
        <div class="flex flex-col">
            <div class="border-b py-4 text-center text-xl tracking-widest uppercase  bg-indigo-600">
                <h3 class="text-2xl font-bold text-center uppercase text-white">
                    <i class="fas fa-unlock-alt"></i> @lang('auth.signin')
                </h3>
            </div>

            <div class="bg-gray-50">
                <div class="text-center sm:text-left w-full p-4">
                    <div>
                        <x-form-input type="text" name="email" placeholder="{{__('auth.field.email')}}"
                                      class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"></x-form-input>
                    </div>
                    <div class="mt-4">
                        <x-form-input name="password" type="password" placeholder="{{__('auth.field.password')}}"/>
                    </div>

                    <div class="mt-1 text-right">
                        <a href="#" class="text-sm text-blue-600 hover:underline">@lang('auth.button.lost_password')</a>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t py-3 px-5 text-sm">
                    <x-form-checkbox name="remember_me" label="{{__('auth.field.remember_me')}}"></x-form-checkbox>
                    <button
                            type="button"
                            class="px-8 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                            onclick="submitForm('form-auth', this)">
                        <i class="fas fa-fingerprint"></i>
                        @lang('auth.button.login')
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-empty-modal>
