<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\AiBackendAvailableBackends;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

trait MakeHttpCalls
{
    /**
     * @param array<string, mixed> $payload
     * @param AiBackendAvailableBackends|null $endpoint
     *
     * @throws RequestException|ConnectionException
     *
     * @return array<string, string>|string
     */
    public function sendRequest(array $payload, AiBackendAvailableBackends|null $endpoint): array|string
    {
        if (is_null($endpoint)) {
            $endpoint = AiBackendAvailableBackends::CDS;
        }

        $cds_url = config()->string('services.ai_backend.endpoint_url');

        $cds_url = $cds_url . $endpoint->value;

        $response = Http::post($cds_url, $payload);
        $response->throw();

        /** @var array<string, string|array<string, string>> $content */
        $content = $response->json();

        return $content['message'];
    }
}
