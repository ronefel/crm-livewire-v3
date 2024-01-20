<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property-read Collection|User[] $users
 * @property-read array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = '';

    public array $search_permissions = [];

    public bool $search_trash = false;

    public Collection $permissionsToSearch;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public int $perPage = 10;

    public function mount()
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        // $this->filterPermissions();
        $this->getPermissions();
    }
    public function render()
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users()
    {
        $this->validate(['search_permissions' => 'exists:permissions,id']);

        $query = User::query()

            ->when($this->search, function ($query) {
                $searchTerm = '%' . strtolower($this->search) . '%';
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->whereRaw('lower(name) like ? or lower(email) like ?', [$searchTerm, $searchTerm]);
                });
            })
            ->when($this->search_permissions, function ($query, $permissionIds) {
                $query->whereHas('permissions', function ($subQuery) use ($permissionIds) {
                    $subQuery->whereIn('id', $permissionIds);
                });
            })
            ->when($this->search_trash, function ($query) {
                $query->onlyTrashed();
            })
            ->orderBy(...array_values($this->sortBy));

        Log::info('SQL: ' . $query->toSql());

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', ],
            ['key' => 'name', 'label' => 'Name', ],
            ['key' => 'email', 'label' => 'Email', ],
            ['key' => 'permissions.key', 'label' => 'Permissions', 'sortable' => false],
        ];
    }

    // public function filterPermissions(?string $value = null)
    // {
    //     $this->permissionsToSearch = Permission::query()->when($value, function (Builder $query) use ($value) {
    //         $query->where('key', 'like', '%' . $value . '%');
    //     })->orderBy('key')->get();

    // }

    private function getPermissions()
    {
        $this->permissionsToSearch = Permission::query()->orderBy('key')->get();
    }

    public function delete(string $id)
    {
        User::find($id)->delete();
    }

    public function restore(string $id)
    {
        User::withTrashed()->find($id)->restore();
    }
}
