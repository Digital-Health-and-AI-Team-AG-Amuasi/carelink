<?php

declare(strict_types=1);

namespace App\Repositories\Auth;

use App\Dto\Auth\CreateUserDto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UsersRepository
{
    public function create(CreateUserDto $dto): User
    {
        return User::create($dto->toArray());
    }

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function list(): LengthAwarePaginator
    {
        return User::with(['roles:id,name'])->paginate();
    }

    /**
     * @param User $user
     * @param Collection<int, Role> $roles
     *
     * @return void
     */
    public function syncUserRoles(User $user, Collection $roles): void
    {
        $user->syncRoles($roles);
    }
}
