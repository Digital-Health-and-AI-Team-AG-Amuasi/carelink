<?php

declare(strict_types=1);

namespace App\Enums;

enum AiBackendAvailableBackends: string
{
    //
    case CDS = 'cds';
    case MESSAGING = 'webhook';
    case UPDATE_PATIENT_PROFILE = 'update_patient_profile';
}
