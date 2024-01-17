<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, get};

it('deve ter uma rota de recuperar a senha', function () {
    get(route('auth.password.recovery'))
        // ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

it('deve solicitar a recuperação de senha e notificar o usuário', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertDontSee(trans('auth.password.recovery'))
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee(trans('auth.password.recovery'));

    Notification::assertSentTo($user, ResetPassword::class);
});

it('deve validar o campo email', function ($value, $rule) {
    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('startPasswordRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'email errado', 'rule' => 'email'],
]);

it('deve criar o token de recuperação de senha', function () {
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});
