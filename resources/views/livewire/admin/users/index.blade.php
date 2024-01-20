<div>
    <x-header title="Users" separator progress-indicator="gotoPage,search,search_permissions">
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" class="input-sm" placeholder="Search..." wire:model.lazy="search" />
            <x-choices-offline class="select-sm" placeholder="Filter by Permissions"
                wire:model.live="search_permissions" :options="$permissionsToSearch" option-label="key" searchable />
        </x-slot:middle>
    </x-header>
    <x-table :headers=" $this->headers" :rows="$this->users" with-pagination>
        @scope('cell_permissions.key', $user)
        @foreach ($user->permissions as $permission)
        <x-badge :value="$permission->key" class="badge-primary" />
        @endforeach
        @endscope

        @scope('actions', $user)
        <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
        @endscope
    </x-table>
</div>
