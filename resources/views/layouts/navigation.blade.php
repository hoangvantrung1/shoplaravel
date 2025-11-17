@php
    $primaryLinks = [
        ['label' => __('Dashboard'), 'route' => 'dashboard', 'match' => 'dashboard'],
    ];
@endphp

<nav x-data="{ open: false }" class="bg-gradient-to-r from-white via-white to-gray-50 border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-6">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white flex items-center justify-center font-bold">
                        AP
                    </div>
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="text-xs text-gray-500">Hệ thống quản trị</span>
                        <span class="text-base font-semibold text-gray-900">Admin Panel</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    @foreach ($primaryLinks as $link)
                        @php $active = request()->routeIs($link['match']); @endphp
                        <a href="{{ route($link['route']) }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition {{ $active ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex items-center space-x-6">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <span class="material-icons text-lg">account_circle</span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="material-icons text-base mr-2 text-emerald-500">manage_accounts</span>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="material-icons text-base mr-2 text-red-500">logout</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-3">
            @foreach ($primaryLinks as $link)
                <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['match'])">
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <div class="pt-4 pb-3 border-t border-gray-100 px-4 space-y-3">
            <div>
                <p class="text-base font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
