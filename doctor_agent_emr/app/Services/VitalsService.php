<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\VitalDto;
use App\Repositories\VitalsRepository;

class VitalsService
{
    public function __construct(
        protected VitalsRepository $vitalsRepository,
    ) {
    }

    /**
     * @param array<int, VitalDto> $vitals
     *
     * @return void
     */
    public function saveVitals(array $vitals): void
    {
        foreach ($vitals as $vital) {
            $this->vitalsRepository->create($vital);
        }
    }
}
