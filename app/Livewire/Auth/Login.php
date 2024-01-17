<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = null;

    public ?string $password = null;

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function tryToLogin()
    {
        $key = Str::transliterate(Str::lower($this->email) . '|' . request()->ip());

        if(RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('rateLimiter', trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($key),
            ]));

            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($key);

            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
