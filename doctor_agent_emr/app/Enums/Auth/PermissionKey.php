<?php

declare(strict_types=1);

namespace App\Enums\Auth;

enum PermissionKey: string
{
    case AccessAuthAndPermissions = 'access auth and permissions';
    case AccessUsers = 'access users';
    case AccessRoles = 'access roles';
    case AccessPermissions = 'access permissions';
}
