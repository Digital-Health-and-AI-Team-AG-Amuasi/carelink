<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        if (!$request->hasHeader(key: 'Api-Key')) {
            return new JsonResponse(data: [
                '_metadata' => ['success' => false],
                'result' => ['message' => 'Authentication Failed. Api-Key was not found.']
            ], status: 401);
        }

        if ($request->header(key: 'Api-Key') !== config(key: 'services.ehr_auth.api_key')) {
            return new JsonResponse(data: [
                '_metadata' => ['success' => false],
                'result' => ['message' => 'Authentication Failed. Api-Key was invalid. ']
            ], status: 401);
        }

        return $next($request);
    }
}
