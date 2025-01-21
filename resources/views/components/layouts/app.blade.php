@use(App\Enums\Can)
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

@if(session('impersonate'))
    <livewire:admin.users.stop-impersonate/>
@endif

{{-- NAVBAR mobile only --}}
<x-nav sticky class="md:hidden">
    <x-slot:brand>
        <x-app-brand/>
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
        </label>
    </x-slot:actions>
</x-nav>

{{--  TOAST area --}}
<x-toast/>

{{-- MAIN --}}
<x-main full-width>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" collapsible class="pt-3 bg-sky-800 text-white">

        @if(!app()->environment('production'))
            <livewire:dev.login/>
        @endif

        <!-- Hidden when collapsed -->
        <div class="hidden-when-collapsed ml-5 font-black text-4xl text-yellow-500">mary</div>

        <!-- Display when collapsed -->
        <div class="display-when-collapsed ml-5 font-black text-4xl text-orange-500">m</div>

        <!-- Custom `active menu item background color` -->
        <x-menu activate-by-route active-bg-color="bg-base-300/10">

            <!-- User -->
            @if($user = auth()->user())
                <x-list-item :item="$user" sub-value="username" no-separator no-hover
                             class="!-mx-2 mt-2 mb-5 border-y border-y-sky-900">
                    <x-slot:actions>
                        <div class="tooltip tooltip-left" data-tip="logoff">
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"
                                      @click="$dispatch('logout')"/>

                        </div>
                    </x-slot:actions>
                </x-list-item>
            @endif

            <x-menu-item title="Home" icon="o-home" link="/"/>

            @can(\App\Enums\Can::BE_AN_ADMIN->value)
                <x-menu-sub title="Admin" icon="o-lock-closed">
                    <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')"/>
                    <x-menu-item title="Users" icon="o-users" :link="route('admin.users')"/>
                </x-menu-sub>
            @endcan

        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>

<livewire:auth.logout/>
</body>
</html>
