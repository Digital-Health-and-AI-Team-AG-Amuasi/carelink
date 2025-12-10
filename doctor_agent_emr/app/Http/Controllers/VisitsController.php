<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SaveVisitRequest;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Repositories\PatientsRepository;
use App\Repositories\VisitsRepository;
use Illuminate\Container\Attributes\Authenticated;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitsController extends Controller
{
    public function __construct(
        protected PatientsRepository $patientsRepository,
        protected VisitsRepository $visitsRepository,
    ) {
    }

    public function index(Patient $patient): View
    {
        $visits = $this->visitsRepository->getVisits($patient);

        return view('patients.visits.index', compact('patient', 'visits'));
    }

    public function create(Patient $patient): View
    {
        $patient->load('pregnancies');

        return view('patients.visits.create', compact('patient'));
    }

    public function store(SaveVisitRequest $request, #[Authenticated] User $user): RedirectResponse
    {
        $this->patientsRepository->createVisit($request->toDto(), $user);

        return to_route('patients.index')
            ->with('success', 'Visit created successfully');
    }

    public function view(Visit $visit): View
    {
        $patient = $visit->patient();
        $vitals = $visit->vitals()->limit(5)->get();
        $medications = $visit->medications()->limit(5)->get();
        $conditions = $visit->conditions()->limit(5)->get();

        return view('visits.view', compact('visit', 'vitals', 'medications', 'conditions', 'patient'));
    }
}
