<?php

namespace App\Core;

interface iRequest {

    public function params();
    public function body();
    public function query();
    public function authorization();

}

class Request implements iRequest {

    private $params, $body, $query;

    public function __construct(array $params = [])
    {
        $this->params = $params;

        if($this->isPOST()) return;

        $content = file_get_contents("php://input");

        if($this->isJSON($content)) return;

        if($this->isFormURL($content)) return;

        $this->decodeMultipartForm($content);
    }

    public function params(): object
    {
        return (object) $this->params;
    }

    public function body(): object
    {
        return (object) $this->body;
    }

    public function query(): object
    {
        return (object) $this->query;
    }

    public function authorization(): string
    {
        $headers = apache_request_headers();
        return $headers["Authorization"] ?? "";
    }

    private function isPOST(): bool
    {
        $this->query = $_GET;
        $this->body = $_POST;

        $filled = !empty($_POST);

        $_GET = [];
        $_POST = [];

        return $filled;
    }

    private function isJSON($content): bool
    {
        $first = substr($content, 0, 1);
        $last = substr($content, -1, 1);

        $valid_scope = $first == "{" && $last == "}";
        
        if(!$valid_scope) return false;
        
        $this->body = (array) json_decode($content);

        return true;
    }

    private function isFormURL($content): bool
    {
        $containsBoundary = stripos($content, "boundary") !== false;

        if($containsBoundary) return false;

        parse_str($content, $data);
        $this->body = $data;

        return true;
    }

    private function decodeMultipartForm($content): void
    {
        $keyword = "\r\nContent-Disposition: form-data;";
        $pieces = explode($keyword, $content);

        $boundary = $pieces[0];

        $pieces = str_replace([
            $boundary, "\n", "\r", "--", 'name="'
        ], "", $pieces);

        $pieces = str_replace('"', "=", $pieces);
        $content = implode("&", $pieces);

        parse_str($content, $data);
        
        $this->body = $data;
    }

}