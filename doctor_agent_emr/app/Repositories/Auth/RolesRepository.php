<?php

declare(strict_types=1);

namespace App\Repositories\Auth;

use App\Dto\Auth\CreateRoleDto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role as RoleContract;

class RolesRepository
{
    /**
     * @return LengthAwarePaginator<int, Role>
     */
    public function list(): LengthAwarePaginator
    {
        return Role::paginate();
    }

    /**
     * @return Collection<int, Role>
     */
    public function listWithoutPagination(): Collection
    {
        return Role::all();
    }

    public function find(int $id): Role
    {
        return Role::findOrFail($id);
    }

    public function create(CreateRoleDto $dto): RoleContract
    {
        return Role::findOrCreate(name: $dto->name, guardName: $dto->guardName);
    }

    public function assignRoleToUser(User $user, RoleContract $role): void
    {
        $user->assignRole($role);
    }

    /**
     * @param RoleContract $role
     * @param Collection<int, Role> $permissions
     *
     * @return void
     */
    public function syncRolePermissions(RoleContract $role, Collection $permissions): void
    {
        $role->syncPermissions($permissions);
    }
}
