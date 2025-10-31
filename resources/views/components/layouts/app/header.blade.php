<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white">

        <flux:header container class="border-b border-rijksgrijs-2 bg-rijksblauw">
            <flux:sidebar.toggle class="text-white lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 ms-2 me-5 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px text-white max-lg:hidden">
                <flux:navbar.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="text-white hover:text-rijksgrijs-1">
                    <span class="text-white">{{ __('Dashboard') }}</span>
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                {{-- space for extra actions --}}

            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="text-white cursor-pointer"
                    :initials="auth()->user()->initials()"
                />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex gap-2 items-center px-1 py-1.5 text-sm text-start">
                                <span class="flex overflow-hidden relative w-8 h-8 rounded-lg shrink-0">
                                    <span
                                        class="flex justify-center items-center w-full h-full text-white rounded-lg bg-rijksblauw"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-sm leading-tight text-start">
                                    <span class="font-semibold truncate">{{ auth()->user()->name }}</span>
                                    <span class="text-xs truncate">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="bg-white lg:hidden border-e border-rijksgrijs-2">
            <flux:sidebar.toggle class="lg:hidden text-rijksblauw" icon="x-mark" />

            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 ms-1 rtl:space-x-reverse" wire:navigate>
                <div class="flex justify-center items-center text-white rounded-md aspect-square size-8 bg-rijksblauw">
                    <x-app-logo-icon class="text-white fill-current size-5" />
                </div>
                <div class="grid flex-1 text-sm ms-1 text-start">
                    <span class="mb-0.5 font-semibold leading-tight truncate text-rijksblauw">WooHub</span>
                </div>
            </a>

            <flux:navlist variant="outline" class="text-rijksblauw">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline" class="text-rijksblauw">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
