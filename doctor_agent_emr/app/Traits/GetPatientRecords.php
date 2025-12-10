<?php

declare(strict_types=1);

namespace App\Traits;

trait GetPatientRecords
{
    /**
     * @param int $patient_id
     *
     * @return array<string, mixed>
     */
    public function share(int $patient_id): array
    {
        return [];
    }
}
