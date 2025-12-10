<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\AiBackendResponseDto;
use App\Dto\AibackendResponseDtoInterface;
use App\Dto\PIPResponseDto;
use App\Enums\AiBackendAvailableBackends;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PIPRequestAction implements RequestActionInterface
{
    /**
     * @param array<string, mixed> $payload
     *
     * @throws ValidationException
     * @throws RequestException
     */
    public function request(array $payload): AibackendResponseDtoInterface
    {

        $pipUrl = config()->string('services.ai_backend.endpoint_url');
        $pipUrl = $pipUrl . AiBackendAvailableBackends::UPDATE_PATIENT_PROFILE->value;

        Log::debug('url ' . $pipUrl);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($pipUrl, $payload);

        Log::debug('url ' . $response);

        $response->throw();

        /**
         * @var array{
         *      status: string,
         *      message: string,
         *      data: null|string|array<mixed>
         *  } $content
         */
        $content = $response->json();

        if (! $content['data']) {
            return AiBackendResponseDto::fromArray($content);
        }

        /**
         * @var array{
         *      status: string,
         *      message: string,
         *      data: array{
         *          patient_phone_number: string,
         *          reminders: array<int, array<string, string>>,
         *      }
         *  } $content
         */
        //        $validatedResponse = ResponseRemindersValidation::validate((array) $response->json());
        return PIPResponseDto::fromArray($content);
    }
}
