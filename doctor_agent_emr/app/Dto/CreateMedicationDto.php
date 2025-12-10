<?php

declare(strict_types=1);

namespace App\Dto;

readonly class CreateMedicationDto
{
    public function __construct(
        public int $visitId,
        public int $drugId,
        public string $frequency,
        public string $duration,
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'visit_id' => $this->visitId,
            'drug_id' => $this->drugId,
            'frequency' => $this->frequency,
            'duration' => $this->duration,
        ];
    }
}
