<?php

namespace App\Core;

interface iResponse {

    public function view(string $view, array $data);
    public function json(array $body);
    public function status(int $code);
    public function message();

}

class Response implements iResponse {

    private $respdata = [
        "code"=> 200,
        "message"=> "OK"
    ];

    public function view(string $view, array $data = []): void
    {
        http_response_code($this->respdata["code"]);
        require_once ROOT . "/app/Views/template.php"; die;
    }

    public function json(array $body = []): void
    {
        header("Content-Type: application/json; charset=utf-8");
        
        http_response_code($this->respdata["code"]);

        $this->respdata["body"] = $body;

        echo json_encode($this->respdata); die;
    }

    public function status(int $code): iResponse
    {
        require_once ROOT . "/app/Views/status.php";

        $this->respdata = $status[$code] ?? $status[409];

        return $this;
    }

    public function message(): string
    {
        return $this->respdata["message"];
    }

}