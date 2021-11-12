<?php

use App\Core\iRequest;
use App\Core\iResponse;

class ErrorController {

    public function index(iRequest $request, iResponse $response)
    {
        $params = $request->params();
        $code = $params->code;

        $response->status($code)->view("error/index", [
            "title"=> "Error {$code}",
            "favicon"=> "error.ico",
            "message"=> $response->message()
        ]);
    }

}