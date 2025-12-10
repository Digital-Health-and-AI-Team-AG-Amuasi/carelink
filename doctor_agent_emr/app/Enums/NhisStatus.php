<?php

declare(strict_types=1);

namespace App\Enums;

enum NhisStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case NOT_APPLICABLE = 'Not Applicable';
}
