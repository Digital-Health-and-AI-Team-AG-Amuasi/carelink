<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AskLLMRequest;
use App\Repositories\PatientsRepository;
use App\Traits\MakeHttpCalls;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;

class AskLLMController extends Controller
{
    use MakeHttpCalls;

    public function __construct(
        protected PatientsRepository $patientsRepository,
    ) {
    }

    public function __invoke(AskLLMRequest $request): JsonResponse
    {

        $requiredAssistant = $request->string('required_assistant')->toString();

        $doctorQuery = $request->string('doctor_query')->toString();
        $patientPhoneNumber = $request->string('patient_phone_number')->toString();
        $sendChatAsContext = $request->boolean('send_chat_as_context');
        $sendPatientProfile = $request->boolean('send_patient_profile');
        $records = $request->json('records');

        if ($sendPatientProfile) {
            $patient = $this->patientsRepository->searchByPhone($patientPhoneNumber);

            if (! $patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested patient was not found.',
                ]);
            }

            $patientHealthRecords = $this->patientsRepository->getPatientMedicalRecords($patient);
        }

        if ($sendPatientProfile && $records) {
            $patientHealthRecords['records'] = $records;
        }

        $payload = [
            'required_assistant' => $requiredAssistant,
            'patient_data' => [
                'patient_phone_number' => $patientPhoneNumber,
                'patient_records' => $patientHealthRecords ?? null,
                'send_chat_as_context' => $sendChatAsContext,
            ],
            'doctor_data' => [
                'doctor_id' => '1245',
                'question' => $doctorQuery,
            ],
        ];

        try {
            $aiBackendResponse = $this->sendRequest($payload, null);

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $aiBackendResponse,
                ],
            ]);

        } catch (ConnectionException|RequestException $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                    'data' => null,
                ]
            );
        }
    }
}
