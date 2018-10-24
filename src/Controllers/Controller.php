<?php

namespace Ximdex\Controllers\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    public function response($message, $data = null, $statusCode = 200)
    {
        $statusCode = $statusCode != 0 ? $statusCode : 500;
        $result = $statusCode < 300 ? 'result' : 'error';

        $response = response();

        if (\Request::isJson()) {
            $response = $response->json([
                'message' => $message,
                $result => $data,
            ], $statusCode);
        } else {
            $data = is_null($data) ? [] : $data;
            $response = $response->view($message, $data, $statusCode);
        }

        return $response;
    }
}
