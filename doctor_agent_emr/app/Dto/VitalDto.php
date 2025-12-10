<?php

declare(strict_types=1);

namespace App\Dto;

class VitalDto
{
    public function __construct(
        public int $vitalTypeId,
        public int|string $vitalTypeValue,
        public string $unitOfMeasurement,
        public int $visitId,
    ) {
    }
}
