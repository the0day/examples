<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">

                <div class="flex-shrink-0 flex items-center">
                    <a href="/">
                        <img class="sm:block hidden lg:hidden h-8"
                             src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
                        <img class="hidden lg:block h-8 w-auto" src="/images/logo-white.png" alt="Jesta">
                    </a>
                </div>
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4 header-navigation">
                        @foreach($navigation as $menu)
                            <a href="{{$menu->getUrl()}}"
                               @if($menu->isActive())
                                   class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium"
                               aria-current="page"
                               @else
                                   class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                                    @endif
                            >{{$menu->getTitle()}}</a>
                        @endforeach

                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                @auth
                    <div class="relative" x-data="{ isOpenedNotifications: false }">
                        <button class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none"
                                type="button"
                                x-on:click="isOpenedNotifications=true">
                            <span class="sr-only">View notifications</span>
                            <!-- Heroicon name: outline/bell -->

                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if (auth()->user()->unreadNotifications)
                                <span class="px-1 text-xs rounded-full absolute bottom-5 left-4 bg-red-500 text-white ring-1 ring-white">
                                    {{auth()->user()->unreadNotifications->count()}}
                                </span>
                            @endif

                        </button>

                        @php
                            $notifications = auth()->user()->notifications;
                        @endphp

                        <div x-show="isOpenedNotifications"
                             x-on:click.away="isOpenedNotifications = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="transition-all w-96 right-0 top-10 absolute max-w-md bg-white shadow-lg rounded-lg pointer-events-auto flex flex-col ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="flex flex-1 px-3 py-2 justify-between items-center">
                                <h3 class="text-base font-medium text-gray-700">@lang('app.notifications.title')</h3>
                                <a href="#" class="text-gray-700 text-xs">@lang('app.notifications.mark_read')</a>
                            </div>
                            @if(!$notifications->count())
                                <div class="flex-1 px-3 pt-2 pb-2 text-sm text-center text-gray-500">
                                    @lang('app.notifications.empty')
                                </div>
                            @else
                                <div class="flex-1">
                                    @foreach($notifications as $notification)
                                        <x-notification-item
                                                image="{{$notification->data['icon'] ?? ''}}"
                                                title="{{$notification->data['title'] ?? ''}}"
                                                date="{{$notification->created_at}}"
                                                body="{{$notification->data['text'] ?? ''}}"
                                                link="{{$notification->link()}}"/>
                                    @endforeach
                                </div>

                            @endif
                        </div>
                    </div>


            </div>

            <!-- Profile dropdown -->
            <div class="ml-3 relative" x-data="{ isOpenedProfileMenu: false }">
                <div x-on:click="isOpenedProfileMenu=true">
                    <button type="button"
                            class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white "
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <x-user.avatar :src="auth()->user()->profile->getAvatarSrc()"/>
                    </button>
                </div>
                <div class="user-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     x-show="isOpenedProfileMenu"
                     x-on:click.away="isOpenedProfileMenu = false"
                     role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                     tabindex="-1">
                    <!-- Active: "bg-gray-100", Not Active: "" -->
                    <a href="{{route('user.profile', auth()->user()->name)}}"
                       class="block px-4 py-2 text-sm text-gray-700" role="menuitem">
                        @lang('account.navigation.my_profile')
                    </a>
                    <a href="{{route('account.personal')}}" class="block px-4 py-2 text-sm text-gray-700"
                       role="menuitem">
                        @lang('account.navigation.my_settings')
                    </a>

                    <form method="POST" action="{{ route('logout') }}" id="form-logout">
                        @csrf
                        <a href="#"
                           onclick="event.preventDefault(); document.getElementById('form-logout').submit();"
                           class="block px-4 py-2 text-sm text-gray-700" role="menuitem">
                            @lang('account.navigation.logout')
                        </a>
                    </form>
                </div>

            </div>
            @endauth

            @guest
                <div class="md:flex items-center justify-end md:flex-1 lg:w-0 ml-8 inline-flex">
                    <a href="#" onclick="showModal('auth')"
                       class="whitespace-nowrap sm:text-base text-white px-3 py-2 mr-1 rounded-md hover:bg-gray-700 text-sm">
                        <i class="fas fa-fingerprint"></i>
                        @lang('auth.signin')
                    </a>
                    <a href="{{route('register')}}"
                       class="tracking-tight text-black whitespace-nowrap justify-center border border-transparent rounded-md bg-yellow-400 hover:bg-yellow-300 px-2 py-1 text-sm sm:text-base">
                        @lang('auth.signup')
                    </a>
                </div>
            @endguest
        </div>
    </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="sm:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @foreach($navigation as $menu)
                <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium"
                   aria-current="page"></a>
            @endforeach
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
        </div>
    </div>
</nav>

<my-app></my-app>
<my-menu></my-menu>