<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreConditionRequest;
use App\Models\Condition;
use App\Models\Patient;
use App\Models\Visit;
use App\Repositories\ConditionsRepository;
use App\Repositories\PatientsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ConditionsRepository $conditionsRepository
     * @param PatientsRepository $patientsRepository
     */
    public function __construct(
        protected ConditionsRepository $conditionsRepository,
        protected PatientsRepository $patientsRepository
    ) {
    }

    public function index(Patient $patient): View
    {
        $conditions = $this->conditionsRepository->getConditions($patient);

        return view('patients.conditions.index', compact('conditions', 'patient'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Visit $visit
     */
    public function create(Visit $visit): View
    {
        $patients = Patient::get();

        return view('patients.conditions.create', compact('visit', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreConditionRequest $request
     * @param Visit $visit
     */
    public function store(StoreConditionRequest $request, Visit $visit): RedirectResponse
    {
        $this->patientsRepository->createCondition($request->toDto(), $visit);

        return redirect()->route('visits.view', $visit)
            ->with('success', 'Condition created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Condition $condition
     */
    public function show(Condition $condition): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Condition $condition
     */
    public function edit(Condition $condition): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Condition $condition
     */
    public function update(Request $request, Condition $condition): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Condition $condition
     */
    public function destroy(Condition $condition): void
    {
        //
    }
}
