<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
    <div wire:loading.delay></div>
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand class="flex gap-2 items-center">
            <x-icon name="o-square-3-stack-3d" class="text-primary" />
            <div>App</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <div class="p-6 pt-3 flex gap-3 items-center h-20">
                <x-icon name="o-square-3-stack-3d" class="text-primary" />
                <div class="hidden-when-collapsed">App</div>
                <x-theme-toggle />
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                <x-list-item :item="$user" sub-value="username" no-separator no-hover
                    class="!-mx-2 mt-2 mb-5 border-y border-y-sky-900">
                    <x-slot:actions>
                        <livewire:auth.logout />
                    </x-slot:actions>
                </x-list-item>
                @endif

                <x-menu-item title="Hello" icon="o-sparkles" link="/" />
                <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-menu-sub>
                @can(\App\Enums\Can::BE_AN_ADMIN->value)
                <x-menu-sub title="Admin" icon="o-lock-closed">
                    <x-menu-item title="Dashboard" icon="o-chart-bar-square" :link="route('admin.dashboard')" />
                </x-menu-sub>
                @endcan
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />
</body>

</html>
