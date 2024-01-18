<div>
    <x-card title="Password Reset" class="mx-auto max-w-[450px]">
        @if($message = session()->get('status'))
        <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
            {{ $message }}
        </x-alert>
        @endif
        <x-form wire:submit="updatePassword">
            <x-input label="Email" value="{{$this->obfuscatedEmail()}}" readonly />
            <x-input label="Email Confirmation" wire:model="email_confirmation" />
            <x-input label="Password" wire:model="password" type="password" />
            <x-input label="Password Confirmation" wire:model="password_confirmation" type="password" />

            <x-slot:actions>
                <x-button label="Reset" class="btn-primary" type="submit" spinner="updatePassword" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
