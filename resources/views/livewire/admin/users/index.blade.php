<div>
    <x-header title="Users" separator progress-indicator="gotoPage,search,search_permissions,sortBy">
        <x-slot:middle class="!justify-end ">
            <div class="flex">
                <x-input icon="o-magnifying-glass" placeholder="Search..." wire:model.lazy="search" />
                <x-choices-offline placeholder="Filter by Permissions" wire:model.live="search_permissions"
                    :options="$permissionsToSearch" option-label="key" searchable />
                <x-checkbox label="Show Deleted Users" wire:model.live="search_trash" />

            </div>

        </x-slot:middle>
    </x-header>
    <x-table :headers=" $this->headers" :rows="$this->users" with-pagination :sort-by="$sortBy">
        @scope('cell_permissions.key', $user)
        @foreach ($user->permissions as $permission)
        <x-badge :value="$permission->key" class="badge-primary" />
        @endforeach
        @endscope

        @scope('actions', $user)
        @unless($user->trashed())
        <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
        @else
        <x-button icon="o-arrow-path-rounded-square" wire:click="restore({{ $user->id }})" spinner
            class="btn-sm btn-success btn-ghost" />
        @endunless
        @endscope
    </x-table>
    <div class="flex justify-end items-center mt-4">
        Records Per Page
        <x-select wire:model.live="perPage" class="select-sm ml-2"
            :options="[['id'=>10,'name'=>10], ['id'=>20,'name'=>20], ['id'=>50,'name'=>50], ['id'=>100,'name'=>100]]" />
    </div>
</div>
