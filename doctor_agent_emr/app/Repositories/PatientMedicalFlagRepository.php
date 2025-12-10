<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\PatientMedicalFlag\CreateFlagDto;
use App\Models\Patient;
use App\Models\PatientMedicalFlags;
use Illuminate\Database\Eloquent\Collection;

class PatientMedicalFlagRepository
{
    public function createFlag(Patient $patient, CreateFlagDto $dto): PatientMedicalFlags
    {
        return PatientMedicalFlags::create([
            'patient_id' => $patient->id,
            'reason' => $dto->reason,
        ]);

    }

    /**
     * @return Collection<int, PatientMedicalFlags>
     */
    public function getLatest(): Collection
    {
        return PatientMedicalFlags::with('patient')
            ->where('is_active', true)
            ->latest()
            ->get();
    }
}
