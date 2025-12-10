<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\AiBackendResponseDto;
use App\Dto\AibackendResponseDtoInterface;
use App\Enums\AiBackendAvailableBackends;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageRequestAction implements RequestActionInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @throws RequestException
     */
    public function request(array $payload): AibackendResponseDtoInterface
    {
        $cds_url = config()->string('services.ai_backend.endpoint_url');

        $cds_url = $cds_url . AiBackendAvailableBackends::MESSAGING->value;

        Log::debug('payload ' . json_encode($payload));

        $response = Http::withHeaders([
            'X-Sender' => 'laravel-job-queue',   // 👈 custom header
        ])->timeout(60)->post($cds_url, $payload);

        $response->throw();

        Log::debug('ai response message ' . $response);

        /**
         * @var array{
         *      status: string,
         *      message: string,
         *      data: null|string|array<mixed>
         *  } $content
         */
        $content = $response->json();

        return AiBackendResponseDto::fromArray($content);

        // TODO: create a dto
    }
}
