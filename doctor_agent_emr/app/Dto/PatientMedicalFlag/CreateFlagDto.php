<?php

declare(strict_types=1);

namespace App\Dto\PatientMedicalFlag;

class CreateFlagDto
{
    public function __construct(
        public string $patientPhone,
        public string $reason,
    ) {
    }
}
