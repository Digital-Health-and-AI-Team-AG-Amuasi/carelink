<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\VitalDto;
use App\Http\Requests\StoreVitalsRequest;
use App\Models\Visit;
use App\Repositories\VitalTypesRepository;
use App\Services\VitalsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VitalsController extends Controller
{
    public function __construct(
        protected VitalTypesRepository $vitalTypesRepository,
        protected VitalsService $vitalsService
    ) {
    }

    public function create(Visit $visit): View
    {
        $vitalTypes = $this->vitalTypesRepository->listWithoutPagination();
        return view('vitals.create', compact('visit', 'vitalTypes'));
    }

    public function store(StoreVitalsRequest $request, Visit $visit): RedirectResponse
    {
        $vitals = [];
        foreach ($request->array('include') as $key => $include) {
            if (! $include) {
                continue;
            }

            $vitals[] = new VitalDto(
                vitalTypeId: $key,
                vitalTypeValue: $request->array('vital_type_value')[$key],
                unitOfMeasurement: $request->array('vital_type_measurement')[$key],
                visitId: $visit->id,
            );
        }

        $this->vitalsService->saveVitals($vitals);

        return to_route('visits.view', $visit->id)
            ->with('success', 'Vitals created successfully');
    }
}
