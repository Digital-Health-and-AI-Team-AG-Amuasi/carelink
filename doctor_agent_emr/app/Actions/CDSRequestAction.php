<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\AiBackendResponseDto;
use App\Dto\AibackendResponseDtoInterface;
use App\Enums\AiBackendAvailableBackends;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class CDSRequestAction implements RequestActionInterface
{
    /**
     * @param array<string, mixed> $payload
     *
     * @throws RequestException
     */
    public function request(array $payload): AibackendResponseDtoInterface
    {
        $cds_url = config()->string('services.ai_backend.endpoint_url');
        $cds_url = $cds_url . AiBackendAvailableBackends::CDS->value;

        $response = Http::post($cds_url, $payload);
        $response->throw();

        /**
         * @var array{
         *      status: string,
         *      message: string,
         *      data: null|string|array<mixed>
         *  } $content
         */
        $content = $response->json();

        return AiBackendResponseDto::fromArray($content);

        //        /** @var array<string, string|array<string, string>> $content */
        //        $content = $response->json();
        //
        //        return $content['message'];
    }
}
