<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;

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

    Notification::assertSentTo($user, PasswordRecoveryNotification::class);
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
