<?php

declare(strict_types=1);

namespace App\Dto\Patients;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\NhisStatus;
use Carbon\Carbon;

class CreatePatientDto
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $phone,
        public string $lhimsNumber,
        public Carbon $edd,
        public Gender $gender,
        public string|null $address,
        public Carbon|null $dob,
        public string|null $notes,
        public string|null $religion,
        public MaritalStatus|null $maritalStatus,
        public NhisStatus|null $nhisStatus,
        public string|null $occupation,
        /**
         * @var string[]
         */
        public array $medicalHistory,
        /**
         * @var string[]
         */
        public array $drugHistory,
        /**
         * @var string[]
         */
        public array $obstetricHistory,
        /**
         * @var string[]
         */
        public array $socialHistory,
    ) {
    }

    /**
     * @return array<string, string|array<string>|null>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'lhims_number' => $this->lhimsNumber,
            'address' => $this->address,
            'dob' => $this->dob?->format('Y-m-d'),
            'gender' => $this->gender->value,
            'notes' => $this->notes,
            'religion' => $this->religion,
            'marital_status' => $this->maritalStatus?->value,
            'nhis_status' => $this->nhisStatus?->value,
            'occupation' => $this->occupation,
            'medical_history' => $this->medicalHistory,
            'drug_history' => $this->drugHistory,
            'obstetric_history' => $this->obstetricHistory,
            'social_history' => $this->socialHistory,
        ];
    }
}
