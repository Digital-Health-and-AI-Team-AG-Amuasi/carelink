<?php

declare(strict_types=1);

namespace App\Repositories\Auth;

use App\Dto\Auth\CreatePermissionDto;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Contracts\Role;

class PermissionsRepository
{
    public function create(CreatePermissionDto $dto): PermissionContract
    {
        return Permission::findOrCreate(name: $dto->name, guardName: $dto->guardName);
    }

    public function find(int $id): PermissionContract
    {
        return Permission::findOrFail($id);
    }

    /**
     * @param Role $role
     * @param Collection<int, Permission> $permissions
     *
     * @return void
     */
    public function givePermissionsToRole(Role $role, Collection $permissions): void
    {
        $role->givePermissionTo($permissions);
    }

    /**
     * @return LengthAwarePaginator<int, Permission>
     */
    public function list(): LengthAwarePaginator
    {
        return Permission::paginate();
    }

    /**
     * @return Collection<int, Permission>
     */
    public function listWithoutPagination(): Collection
    {
        return Permission::all();
    }
}
