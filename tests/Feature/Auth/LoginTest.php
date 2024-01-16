<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});

it('deve fazer login', function () {
    $user = User::factory()->create([
        'email'    => 'rone@santos.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'rone@santos.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('deve mostrar erro no login', function () {
    Livewire::test(Login::class)
        ->set('email', 'rone@santos.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));
});

it('deve bloquear o login após 5 tentativas', function () {
    $user = User::factory()->create();

    for($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password-errado')
            ->call('tryToLogin')
            ->assertHasErrors(['invalidCredentials'])
            ->assertSee(trans('auth.failed'));
    }

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password-errado')
        ->call('tryToLogin')
        ->assertHasErrors(['rateLimiter']);
});
