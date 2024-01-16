<x-card class="mx-auto max-w-[350px]">
    <x-form wire:submit="submit">
        <x-input label="Nome" wire:model="name" />
        <x-input label="Email" wire:model="email" />
        <x-input label="Confirmar Email" wire:model="email_confirmation" />
        <x-input label="Senha" wire:model="password" type="password" />

        <x-slot:actions>
            <x-button label="Cancelar" link="{{route('login')}}" />
            <x-button label=" Salvar" class="btn-primary" type="submit" spinner="submit" />
        </x-slot:actions>
    </x-form>
</x-card>
