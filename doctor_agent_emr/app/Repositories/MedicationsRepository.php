<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreateMedicationDto;
use App\Models\Medication;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;

class MedicationsRepository
{
    /**
     * @param Visit $visit
     *
     * @return Collection<int, Medication>
     */
    public function getMedsByVisit(Visit $visit): Collection
    {
        return $visit->medications()->get();
    }

    public function create(CreateMedicationDto $dto): Medication
    {
        return Medication::create($dto->toArray());
    }
}
