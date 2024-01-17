<div>
    <x-card title="Login" class="mx-auto max-w-[450px]">
        @error('invalidCredentials')
        <x-alert icon="o-exclamation-triangle" class="mb-4">{{$message}}</x-alert>
        @enderror
        @error('rateLimiter')
        <x-alert icon="o-exclamation-triangle" class="alert-warning mb-4">{{$message}}</x-alert>
        @enderror
        <x-form wire:submit="tryToLogin">
            <x-input label="Email" wire:model="email" />
            <x-input label="Senha" wire:model="password" type="password" />

            <x-slot:actions>
                <x-button label="I want to creat an account" link="{{route('auth.register')}}" class="btn-ghost" />
                <x-button label="Login" class="btn-primary" type="submit" spinner="tryToLogin" />
            </x-slot:actions>
        </x-form>
    </x-card>
    <div class="w-full flex justify-center mt-4 text-sm">
        <a href="{{route('password.recovery')}}" class="link">Forgot your password?</a>
    </div>
</div>
