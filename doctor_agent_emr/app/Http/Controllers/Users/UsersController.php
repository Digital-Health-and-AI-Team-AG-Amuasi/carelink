<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\UpdateUserRolesRequest;
use App\Models\User;
use App\Repositories\Auth\RolesRepository;
use App\Repositories\Auth\UsersRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class UsersController extends Controller
{
    public function __construct(
        protected RolesRepository $rolesRepository,
        protected PermissionRegistrar $permissionRegistrar,
        protected UsersRepository $usersRepository,
    ) {
        $this->permissionRegistrar->forgetCachedPermissions();
    }

    public function index(): View
    {
        $users = $this->usersRepository->list();

        return view('users.list', compact('users'));
    }

    public function create(): View
    {
        $roles = $this->rolesRepository->listWithoutPagination();

        return view('users.create', compact('roles'));
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $user = $this->usersRepository->create($request->toDto());
        $this->rolesRepository->assignRoleToUser(user: $user, role: $this->rolesRepository->find($request->integer('role')));

        return redirect()
            ->route('auth.users.index')
            ->with('success', 'User successfully created!');
    }

    public function roles(User $user): View
    {
        $roles = $this->rolesRepository->listWithoutPagination();
        $userRoles = $user->roles()->pluck('id');

        return view('users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $roles = collect();
        foreach ($request->array('roles') as $roleId) {
            $roles->add($this->rolesRepository->find((int) $roleId));
        }

        $this->usersRepository->syncUserRoles($user, $roles);

        return to_route('auth.users.roles', $user->id)
            ->with('success', sprintf('Roles updated successfully for user "%s %s"', $user->first_name, $user->last_name));
    }
}
