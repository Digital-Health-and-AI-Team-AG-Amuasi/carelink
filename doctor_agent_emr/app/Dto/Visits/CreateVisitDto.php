<?php

declare(strict_types=1);

namespace App\Dto\Visits;

use App\Enums\PregnancyState;
use Carbon\Carbon;
use InvalidArgumentException;

class CreateVisitDto
{
    public function __construct(
        public string|null $reasonForVisit,
        public PregnancyState $pregnancyState,
        public Carbon|null $edd,
        public int|null $pregnancyId,
        public int|null $patientId,
    ) {
        if ($pregnancyState === PregnancyState::New && $edd === null) {
            throw new InvalidArgumentException('EDD is required for new pregnancy');
        }

        if ($pregnancyState === PregnancyState::Old && $pregnancyId === null) {
            throw new InvalidArgumentException('Pregnancy ID is required for existing pregnancy');
        }

        if ($this->pregnancyState === PregnancyState::New && $this->patientId === null) {
            throw new InvalidArgumentException('Patient ID is required for new pregnancy');
        }
    }
}
