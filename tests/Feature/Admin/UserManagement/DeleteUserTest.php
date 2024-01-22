<?php

use App\Livewire\Admin\Users\Delete;
use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('deve deletar um usuário', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('deve confirmar antes de deletar o usuário', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);
});

it('deve mandar notificação para o usuário deletado', function () {
    Notification::fake();
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);
    Livewire::test(Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'DELETAR')
        ->call('destroy');

    Notification::assertSentTo(
        $forDeletion,
        UserDeletedNotification::class
    );
});
