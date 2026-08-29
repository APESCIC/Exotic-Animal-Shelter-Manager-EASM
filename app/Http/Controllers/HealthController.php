<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Report application health and version for hosting checks.
     */
    public function __invoke(): JsonResponse
    {
        $database = $this->databaseIsReachable();

        return response()->json([
            'status' => $database ? 'ok' : 'degraded',
            'app' => config('app.name'),
            'version' => config('app.version'),
            'checks' => [
                'app' => true,
                'database' => $database,
            ],
        ], $database ? 200 : 503);
    }

    private function databaseIsReachable(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
