<?php

use App\Livewire\Auth\Register;
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
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'rone santos',
        'email' => 'rone@santos.com',
    ]);

    assertDatabaseCount('users', 1);
});
