<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\PIPRequestAction;
use App\Dto\PIPResponseDto;
use App\Models\Patient;
use App\Repositories\ReminderRepository;
use App\Traits\MakeHttpCalls;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;

class UpdateAIPatientProfileJob implements ShouldQueue
{
    use MakeHttpCalls;
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param Patient $patient
     */
    public function __construct(
        protected Patient $patient,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @param ReminderRepository $reminderRepository
     */
    public function handle(ReminderRepository $reminderRepository): void
    {
        $patientName = $this->patient->first_name . ' ' . $this->patient->last_name;
        $patientPhone = $this->patient->phone;
        $newClinicalRecommendations = $this->patient->patientRecords()->latest()->value('plan');

        $payload = [
            'patient_name' => $patientName,
            'patient_phone_number' => $patientPhone,
            'new_management_plan' => $newClinicalRecommendations,
        ];

        // Make request to ai-backend to get list of reminders
        try {

            $responseData = (new PIPRequestAction())->request($payload);

            if ($responseData instanceof PIPResponseDto) {
                $reminderRepository->create(
                    $responseData->reminders_dto,
                    $this->patient,
                );
            }

        } catch (RequestException|ValidationException $e) {
            return;
        }

    }
}
