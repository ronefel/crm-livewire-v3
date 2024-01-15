<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

it('deve registrar um novo usuário', function () {
    Livewire::test(Register::class)
        ->set('name', 'rone santos')
        ->set('email', 'rone@santos.com')
        ->set('email_confirmation', 'rone@santos.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(RouteServiceProvider::HOME);

    assertDatabaseHas('users', [
        'name'  => 'rone santos',
        'email' => 'rone@santos.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())
        ->id->toBe(User::first()->id);
});

it('validando rules', function ($c) {
    Livewire::test(Register::class)
        ->set($c->campo, $c->valor)
        ->call('submit')
        ->assertHasErrors([$c->campo => $c->rule]);
})->with([
    'name::required'     => (object)['campo' => 'name', 'valor' => '', 'rule' => 'required'],
    'name::max:255'      => (object)['campo' => 'name', 'valor' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required'    => (object)['campo' => 'email', 'valor' => '', 'rule' => 'required'],
    'email::email'       => (object)['campo' => 'email', 'valor' => 'não-é-email', 'rule' => 'email'],
    'email::max:255'     => (object)['campo' => 'email', 'valor' => str_repeat('*' . '@email.com', 256), 'rule' => 'max'],
    'email::confirmed'   => (object)['campo' => 'email', 'valor' => 'rone@santos.com', 'rule' => 'confirmed'],
    'password::required' => (object)['campo' => 'password', 'valor' => '', 'rule' => 'required'],
]);
