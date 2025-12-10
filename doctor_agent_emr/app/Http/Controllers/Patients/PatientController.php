<?php

declare(strict_types=1);

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\RetrievePatientApiRequest;
use App\Http\Requests\UpdatePatientRecordsRequest;
use App\Models\Patient;
use App\Repositories\ConditionsRepository;
use App\Repositories\MedicationsRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\PregnanciesRepository;
use App\Repositories\VisitsRepository;
use App\Repositories\VitalsRepository;
use App\Traits\GetPatientRecords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    use GetPatientRecords;

    public function __construct(
        protected PatientsRepository $patientsRepository,
        protected VisitsRepository $visitsRepository,
        protected MedicationsRepository $medicationsRepository,
        protected VitalsRepository $vitalsRepository,
        protected PregnanciesRepository $pregnanciesRepository,
        protected ConditionsRepository $conditionsRepository
    ) {
    }

    public function index(Request $request): View
    {
        $query = Patient::query();
        if ($request->filled('search_term')) {
            $searchTerm = $request->string('search_term')->toString();
            $query->where('phone', 'like', '%' . $searchTerm . '%');

        }

        $patients = $query->paginate(8);
        return view('patients.index', compact('patients'));
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(CreatePatientRequest $request): RedirectResponse
    {
        $patient = $this->patientsRepository->create($request->toDto());

        return to_route('patients.review', compact('patient'))
            ->with('success', 'Patient created successfully');
    }

    public function view(Patient $patient): View
    {
        $pregnancies = $this->pregnanciesRepository->listWithoutPagination(5);
        $visits = $this->visitsRepository->getVisits($patient);
        $vitals = $this->patientsRepository->getPatientVitalsHistoryWithoutPagination($patient, 5);
        $medications = $this->patientsRepository->getPatientMedicationWithoutPagination($patient, 5);
        $conditions = $this->conditionsRepository->getConditions($patient, 5);

        return view('patients.view', compact('patient', 'pregnancies', 'visits', 'vitals', 'medications', 'conditions'));
    }

    // Show the form with patient list
    public function showForm(): View
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'phone')->get();

        return view('cds', compact('patients'));
    }

    public function getPatientsData(): View
    {
        $patients_data = Patient::select('id')->get()->map(fn ($patient) => $this->share($patient->id));

        return view('records', ['patients_data' => $patients_data]);
    }

    public function search(RetrievePatientApiRequest $request): JsonResponse
    {

        $patientPhoneNumber = $request->string('patient_phone_number')->toString();

        $patient = $this->patientsRepository->searchByPhone($patientPhoneNumber);

        if (! $patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Patient found.',
            'data' => $patient,
        ]);
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRecordsRequest $request, Patient $patient): RedirectResponse
    {
        $patient = $this->patientsRepository->update($request->toDto(), $patient);

        return redirect()->route('patients.review', compact('patient'))->with('success', 'Patient updated successfully');
    }

    public function deleteAllSoftDeletedPatients(): JsonResponse
    {
        // Permanently delete all soft-deleted patients
        $patients = Patient::onlyTrashed()->doesntHave('pregnancies')->get();

        foreach ($patients as $patient) {
            $patient->forceDelete();
        }

        return response()->json([
            'message' => 'Deleted patients'
        ]);

    }

}
