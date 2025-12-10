<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\VitalDto;
use App\Models\Vital;

class VitalsRepository
{
    public function create(VitalDto $dto): Vital
    {
        return Vital::create([
            'vital_type_id' => $dto->vitalTypeId,
            'value' => $dto->vitalTypeValue,
            'unit_of_measurement' => $dto->unitOfMeasurement,
            'visit_id' => $dto->visitId,
        ]);
    }
}
