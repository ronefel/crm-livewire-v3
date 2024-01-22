<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Delete extends Component
{
    public User $user;

    public bool $modal = false;

    #[Rule(['required', 'confirmed'])]
    public string $confirmation = 'DELETAR';

    public ?string $confirmation_confirmation = null;

    public function render()
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy()
    {
        $this->validate();

        $this->user->delete();
        $this->user->notify(new UserDeletedNotification());

        $this->dispatch('user::deleted');
    }
}
