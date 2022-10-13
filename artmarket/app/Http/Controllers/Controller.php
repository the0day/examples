<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function user(): User
    {
        return Auth::user();
    }

    public function success(string $message = '', $data = [], int $code = 200): JsonResponse
    {
        return $this->response($message, $data, $code);
    }

    private function response(string $message = '', $data = [], int $code = 200, bool $success = true): JsonResponse
    {
        $response['success'] = $success;
        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== []) {
            $response['data'] = $data;
        }

        $response['status'] = $code;

        return response()->json($response, $code);
    }

    public function error(string $message = '', $data = [], int $code = 500): JsonResponse
    {
        return $this->response($message, $data, $code, false);
    }
}
