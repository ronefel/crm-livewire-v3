<div>
    <x-card title="Password recovery" class="mx-auto max-w-[450px]">
        @if ($message)
        <x-alert icon="o-envelope" class="alert-success mb-4">{{$message}}</x-alert>
        @endif

        <x-form wire:submit="startPasswordRecovery">
            <x-input label="Email" wire:model="email" />

            <x-slot:actions>
                <x-button label="To Login Page" link="{{route('login')}}" />
                <x-button label="Submit" class="btn-primary" type="submit" spinner="tryToLogin" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
