<?php

use App\Livewire\Admin\Users\Delete;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertSoftDeleted;

it('deve deletar um usuário', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    Livewire::test(Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', ['id' => $forDeletion->id]);
});
