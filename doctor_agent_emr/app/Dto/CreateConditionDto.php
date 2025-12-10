<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\Carbon;

class CreateConditionDto
{
    public function __construct(
        public int $patientId,
        public string|null $diseaseDiagnosis,
        public string $diseaseDescription,
        public Carbon|null $diseaseStartedAt,
        public Carbon|null $diseaseEndedAt,
        public bool $isDiseaseActive,
        public string|null $doctorsNotes,
    ) {
    }
}
