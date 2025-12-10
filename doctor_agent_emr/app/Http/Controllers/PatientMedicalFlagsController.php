<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientMedicalFlagRequest;
use App\Models\PatientMedicalFlags;
use App\Repositories\PatientMedicalFlagRepository;
use App\Repositories\PatientsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientMedicalFlagsController extends Controller
{
    public function __construct(
        protected PatientMedicalFlagRepository $patientMedicalFlagRepository,
        protected PatientsRepository $patientsRepository,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $flags = $this->patientMedicalFlagRepository->getLatest();

        return response()->json([
            'success' => true,
            'data' => $flags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
    }

    public function showEmergencyPage(): View
    {
        $priorityCases = $this->patientMedicalFlagRepository->getLatest();

        return view('emergency.index', [
            'priorityCases' => $priorityCases->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePatientMedicalFlagRequest $medicalFlagRequest
     */
    public function store(StorePatientMedicalFlagRequest $medicalFlagRequest): PatientMedicalFlags
    {
        $medicalFlagDto = $medicalFlagRequest->toDto();
        $patient = $this->patientsRepository->searchByPhone($medicalFlagDto->patientPhone);

        if (!$patient) {
            throw new ModelNotFoundException();
        }

        return $this->patientMedicalFlagRepository->createFlag(
            $patient,
            $medicalFlagRequest->toDto()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param PatientMedicalFlags $patientMedicalFlags
     */
    public function show(PatientMedicalFlags $patientMedicalFlags): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PatientMedicalFlags $patientMedicalFlags
     */
    public function edit(PatientMedicalFlags $patientMedicalFlags): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PatientMedicalFlags $patientMedicalFlags
     */
    public function update(Request $request, PatientMedicalFlags $patientMedicalFlags): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PatientMedicalFlags $patientMedicalFlags
     */
    public function destroy(PatientMedicalFlags $patientMedicalFlags): void
    {
        //
    }
}
