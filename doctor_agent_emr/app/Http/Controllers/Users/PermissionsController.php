<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Auth\PermissionsRepository;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class PermissionsController extends Controller
{
    public function __construct(
        protected PermissionRegistrar $permissionRegistrar,
        protected PermissionsRepository $permissionsRepository,
    ) {
        $this->permissionRegistrar->forgetCachedPermissions();
    }

    public function index(): View
    {
        $permissions = $this->permissionsRepository
            ->list();

        return view('permissions.list', compact('permissions'));
    }
}
