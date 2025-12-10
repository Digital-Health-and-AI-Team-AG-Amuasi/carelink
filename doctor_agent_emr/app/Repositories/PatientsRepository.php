<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreateConditionDto;
use App\Dto\Patients\CreatePatientDto;
use App\Dto\Patients\CreatePatientRecordDto;
use App\Dto\Visits\CreateVisitDto;
use App\Enums\PregnancyState;
use App\Models\Condition;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Patient\PatientRecord;
use App\Models\Pregnancy;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientsRepository
{
    public function __construct(
        protected PregnanciesRepository $pregnanciesRepository,
    ) {
    }

    /**
     * @param array<string, string|int> $filters
     *
     * @return LengthAwarePaginator<int, Patient>
     */
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Patient::query();
        $this->applyFilters($query, $filters);

        return $query->latest()->paginate();
    }

    public function create(CreatePatientDto $dto): Patient
    {
        $patient = Patient::create($dto->toArray());

        $this->pregnanciesRepository->createPregnancy(
            $patient,
            $dto->edd
        );

        return $patient;
    }

    public function update(CreatePatientDto $dto, Patient $patient): Patient
    {
        $patient->update($dto->toArray());

        $this->pregnanciesRepository->updatePregnancy($patient, $dto->edd);

        return $patient;
    }

    public function findOrFail(int $id): Patient
    {
        return Patient::findOrFail($id);
    }

    public function createPatientRecord(CreatePatientRecordDto $dto, Patient $patient, User $user): PatientRecord
    {
        // Get the latest visit for the given patient
        $latestVisit = $patient->visits()
            ->latest()
            ->first();

        return PatientRecord::create([
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'visit_id' => $latestVisit?->id,
            'current_complains' => $dto->currentComplains,
            'on_direct_questions' => $dto->onDirectQuestions,
            'issues' => $dto->issues,
            'updates' => $dto->updates,
            'on_examinations' => $dto->onExaminations,
            'vitals' => $dto->vitals,
            'investigations' => $dto->investigations,
            'impression' => $dto->impression,
            'plan' => $dto->plan,
            'history_presenting_complains' => $dto->history_presenting_complains,
        ]);
    }

    /**
     * @param CreateVisitDto $dto
     * @param User $recordedBy
     *
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function createVisit(CreateVisitDto $dto, User $recordedBy): Visit
    {
        if ($dto->pregnancyState === PregnancyState::New) {
            // create a new pregnancy
            throw_if(
                $dto->edd === null,
                new ModelNotFoundException('Visit not found for the given patient.')
            );

            $pregnancy = $this->pregnanciesRepository->createPregnancy(
                $this->findOrFail(intval($dto->patientId)),
                $dto->edd
            );
        } else {
            throw_if(
                $dto->pregnancyId === null,
                new \InvalidArgumentException('Pregnancy ID is required for existing pregnancy')
            );
            $pregnancy = $this->pregnanciesRepository->find($dto->pregnancyId);
        }

        return Visit::create([
            'pregnancy_id' => $pregnancy?->id,
            'reason' => $dto->reasonForVisit,
            'staff_id' => $recordedBy->id,
        ]);
    }

    public function createCondition(CreateConditionDto $dto, Visit $visit): Condition
    {
        return Condition::create([
            'visit_id' => $visit->id,
            'patient_id' => $dto->patientId,
            'diagnosis' => $dto->diseaseDiagnosis,
            'description' => $dto->diseaseDescription,
            'started_at' => $dto->diseaseStartedAt,
            'ended_at' => $dto->diseaseEndedAt,
            'is_active' => $dto->isDiseaseActive,
            'notes' => $dto->doctorsNotes,
        ]);
    }

    /**
     * @param Patient $patient
     * @param int|null|null $limit
     *
     * @return Collection<int, Vital>
     */
    public function getPatientVitalsHistoryWithoutPagination(Patient $patient, int|null $limit = null): Collection
    {
        return Vital::query()
            ->with('vitalType')
            ->whereRelation('visit', 'pregnancy_id', $patient->pregnancies()->pluck('id')->toArray())
            ->when($limit, static fn (Builder $query, int $limit) => $query->limit($limit))
            ->latest()
            ->get();
    }

    /**
     * @param Patient $patient
     * @param int|null|null $limit
     *
     * @return Collection<int, Medication>
     */
    public function getPatientMedicationWithoutPagination(Patient $patient, int|null $limit = null): Collection
    {
        return Medication::query()
            ->with('drug')
            ->whereRelation('visit', 'pregnancy_id', $patient->pregnancies()->pluck('id')->toArray())
            ->when($limit, static fn (Builder $query, int $limit) => $query->limit($limit))
            ->latest()
            ->get();
    }

    /**
     * @param Builder<Patient> $query
     * @param array<string, string|int> $filters
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }
    }

    /**
     * @param Patient $patient
     *
     * @return array{
     *    patient: Patient,
     *    pregnancies: Collection<int, Pregnancy>,
     *    visits: Collection<int, Visit>
     * }
     */
    public function getPatientMedicalRecords(Patient $patient): array
    {
        return
            [
                'patient' => $patient,
                'pregnancies' => $patient->pregnancies,
                'visits' => $patient->visits,
                'conditions' => $patient->conditions,
                'records' => $patient->patientRecords,
            ];
    }

    public function searchByPhone(string $phone): Patient|null
    {
        return Patient::with('visits.vitals.vitalType', 'visits.medications.drug', 'patientRecords')->where('phone', $phone)->first();
    }
}
