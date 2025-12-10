<?php

declare(strict_types=1);

namespace App\Enums;

enum MaritalStatus: string
{
    case Married = 'married';
    case Single = 'single';
    case Divorced = 'divorced';
    case Widowed = 'widowed';
}
