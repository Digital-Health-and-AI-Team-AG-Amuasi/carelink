<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateRoleRequest;
use App\Http\Requests\Auth\UpdateRolePermissionsRequest;
use App\Models\Role;
use App\Repositories\Auth\PermissionsRepository;
use App\Repositories\Auth\RolesRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class RolesController extends Controller
{
    public function __construct(
        protected RolesRepository $rolesRepository,
        protected PermissionsRepository $permissionsRepository,
        protected PermissionRegistrar $permissionRegistrar,
    ) {
        $this->permissionRegistrar->forgetCachedPermissions();
    }

    public function index(): View
    {
        $pageData['pageTitle'] = 'Roles';
        $roles = $this->rolesRepository->list();

        return view('roles.list', compact('roles', 'pageData'));
    }

    public function create(): View
    {
        $pageData['pageTitle'] = 'Create Role';

        return view('roles.create', compact('pageData'));
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $this->rolesRepository->create($request->toDto());

        return redirect()
            ->route('auth.roles.index')
            ->with('success', 'Role added successfully');
    }

    public function permissions(Role $role): View
    {
        $rolePermissions = $role->permissions()->pluck('id');
        $permissions = $this->permissionsRepository->listWithoutPagination();

        return view('roles.permissions', compact('role', 'role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        $permissions = collect();
        foreach ($request->array('permissions') as $permissionId) {
            $permissions->add($this->permissionsRepository->find((int) $permissionId));
        }

        $this->rolesRepository->syncRolePermissions($role, $permissions);

        return to_route('auth.roles.permissions.update', $role)
            ->with('success', sprintf('Permissions updated successfully for "%s" role', $role->name));
    }
}
