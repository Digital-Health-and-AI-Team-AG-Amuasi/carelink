<?php

declare(strict_types=1);

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientRecordRequest;
use App\Jobs\UpdateAIPatientProfileJob;
use App\Models\Patient;
use App\Models\Patient\PatientRecord;
use App\Models\User;
use App\Models\Visit;
use App\Repositories\PatientsRepository;
use Illuminate\Container\Attributes\Authenticated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientRecordsController extends Controller
{
    public function __construct(
        protected PatientsRepository $patientsRepository
    ) {
    }

    /**
     * Display a listing of the encounters.
     *
     * @param Patient $patient
     */
    public function index(Patient $patient): View
    {
        $patient = $patient->load(['patientRecords.visit', 'patientRecords.user']);

        return view('patients.review.index', compact('patient'));
    }

    public function store(CreatePatientRecordRequest $request, Patient $patient, #[Authenticated] User $user): RedirectResponse
    {
        $latestVisit = $patient->visits()->latest()->first();

        if ($latestVisit === null) {
            Visit::create([
                'pregnancy_id' => $patient->pregnancies()->latest()->first()?->id,
                'reason' => 'Regular hospital visit for checkup',
                'staff_id' => $user->id,
            ]);
        }

        $this->patientsRepository->createPatientRecord($request->toDto(), $patient, $user);

        // Dispatch job to create and store reminders
        UpdateAIPatientProfileJob::dispatch($patient);

        return to_route('patients.review', compact('patient'))
            ->with('success', 'Review added successfully');
    }

    public function create(Patient $patient): View
    {
        $latestVisit = $patient->visits()->latest()->first();
        $disableForm = ! $latestVisit;

        return view('patients.review.create', compact('patient', 'disableForm'));
    }

    public function show(Patient $patientRecord): View
    {
        return view('patients.review.view', compact('patientRecord'));
    }

    public function destroy(Patient $patient, PatientRecord $patientRecord): RedirectResponse
    {
        $patientRecord->delete();

        return redirect()->route('patients.review', compact('patient'))->with('success', 'Record deleted successfully');
    }

    public function update(Request $request, Patient $patient, PatientRecord $patientRecord): RedirectResponse
    {
        $patientRecord->update($request->all());

        return redirect()->route('patients.review', compact('patientRecord', 'patient'))
            ->with('success', 'Patient updated successfully');
    }

    public function edit(PatientRecord $patientRecord): PatientRecord
    {
        return $patientRecord;
    }
}
