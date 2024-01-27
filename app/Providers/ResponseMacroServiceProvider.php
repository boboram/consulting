<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

/**
 * Class ResponseMacroServiceProvider
 * @package App\Providers
 */
class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        //
    }


    /**
     * Register the application's response macros.
     */
    public function boot()
    {
        Response::macro(
            'success',
            function ($result = [], int $statusCode = HttpResponse::HTTP_OK) {
                $response = [
                    'result_code' => 0,
                    'message' => '성공',
                    'response' => new \stdClass(),
                ];
                if (!empty($result)) {
                    $response['response'] = $result;
                }
                if (\request()->has('next_action')) {
                    $response['next_action'] = \request()->input('next_action');
                }

                return Response::make($response, $statusCode);
            }
        );

        Response::macro(
            'error',
            function (
                string $message,
                int $statusCode = HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                ?Request $request = null
            ) {
                $response = [
                    'result_code' => 1,
                    'message' => $message,
                    'response' => new \stdClass(),
                    'developer_message' => app('developer-message')->info(),
                ];

                if ($statusCode !== HttpResponse::HTTP_INTERNAL_SERVER_ERROR) {
                    $statusCode = HttpResponse::HTTP_OK;
                }

                return Response::make($response, $statusCode);
            }
        );
    }
}
