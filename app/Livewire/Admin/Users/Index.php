<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use WithPagination;

    public ?string $search = '';

    public function mount()
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
    }
    public function render()
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%' . strtolower($this->search) . '%';
                $query->whereRaw('lower(name) like ? or lower(email) like ?', [$searchTerm, $searchTerm]);
            })
            ->paginate();
    }

    #[Computed]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'permissions.key', 'label' => 'Permissions', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ];
    }
}
