<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('welcome'))->name('welcome');

Route::get('/home', fn () => redirect()->route('welcome'))->name('home');

// WooWijzer Routes
Route::get('/over', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('about'))->name('about');

Route::get('/document-samenvatten', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('document-summarize'))->name('document.summarize');

Route::get('/contact', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('contact'))->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
