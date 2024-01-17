<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    // public ?string $email = null;

    public function mount()
    {
        $this->token = request('token');
        // $this->email = request()->get('email');

        if($this->tokenNotValid()) {
            $this->redirectRoute('login');
        }
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenNotValid()
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        foreach($tokens as $t) {
            if(Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }
}
