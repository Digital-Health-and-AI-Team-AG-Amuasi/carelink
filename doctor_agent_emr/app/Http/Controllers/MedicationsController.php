<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateMedicationRequest;
use App\Models\Visit;
use App\Repositories\DrugsRepository;
use App\Repositories\MedicationsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MedicationsController extends Controller
{
    public function __construct(
        protected MedicationsRepository $medicationsRepository,
        protected DrugsRepository $drugsRepository,
    ) {
    }

    public function create(Visit $visit): View
    {
        $meds = $this->medicationsRepository->getMedsByVisit($visit);
        $drugs = $this->drugsRepository->listWithoutPagination();
        return view('medications.create', compact('visit', 'meds', 'drugs'));
    }

    public function store(Visit $visit, CreateMedicationRequest $request): RedirectResponse
    {
        $this->medicationsRepository->create($request->toDto());

        return redirect()->route('visits.medications.create', $visit)->with('success', 'Medication added successfully.');
    }
}
