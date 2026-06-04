<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = null, $message = 'OK', $status = 200) {
            return response()->json(['success' => true, 'message' => $message, 'data' => $data], $status);
        });

        Response::macro('error', function ($message = 'Error', $status = 500, $errors = null) {
            $payload = ['success' => false, 'message' => $message];
            if (!is_null($errors)) {
                $payload['errors'] = $errors;
            }
            return response()->json($payload, $status);
        });
    }
}
