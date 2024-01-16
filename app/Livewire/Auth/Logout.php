<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function render()
    {
        return <<<BLADE
            <div>
                <x-button
                    wire:click="logout"
                    icon="o-power"
                    class="btn-circle btn-ghost btn-xs"
                    tooltip-left="logoff"
                    spinner="logout"
                />
            </div>
        BLADE;
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('login');
    }
}
