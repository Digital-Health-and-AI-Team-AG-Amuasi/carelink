<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Dto\Auth\CreatePermissionDto;
use App\Enums\Auth\PermissionKey;
use App\Repositories\Auth\PermissionsRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\Permission\PermissionRegistrar;

class CreateNonExistingPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-non-existing-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create non-existing permissions';

    /**
     * Execute the console command.
     *
     * @param PermissionsRepository $permissionsRepository
     *
     * @throws BindingResolutionException
     */
    public function handle(PermissionsRepository $permissionsRepository): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach (PermissionKey::cases() as $permissionKey) {
            $this->info("Find or create permission: {$permissionKey->value}");
            $permissionsRepository->create(new CreatePermissionDto(name: $permissionKey->value));
        }
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
