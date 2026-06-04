<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        $isJson = $request->expectsJson();

        if ($isJson) {
            $status = 500;
            $message = 'Server Error';

            if ($e instanceof QueryException || $this->isDatabaseException($e)) {
                $status = 503;
                $message = 'Database not configured or unreachable.';
            }

            $payload = ['success' => false, 'message' => $message];

            if (config('app.debug')) {
                $payload['exception'] = get_class($e);
                $payload['details'] = $e->getMessage();
            }

            return response()->json($payload, $status);
        }

        if ($e instanceof QueryException || $this->isDatabaseException($e)) {
            return response()->make('<h1>Service Unavailable</h1><p>Database not configured or unreachable.</p>', 503);
        }

        return parent::render($request, $e);
    }

    /**
     * Heuristic to detect low-level DB connection exceptions.
     */
    protected function isDatabaseException(Throwable $e): bool
    {
        $class = get_class($e);
        return str_contains($class, 'PDO') || str_contains($class, 'Connection') || str_contains($class, 'Database');
    }
}
