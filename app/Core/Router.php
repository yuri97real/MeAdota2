<?php

namespace App\Core;

class Router {

    private $request_method, $url, $current;
    
    private $requests = [
        "GET"=> [],
        "POST"=> [],
        "PUT"=> [],
        "DELETE"=> []
    ];

    public function __construct()
    {
        $this->request_method = $_SERVER["REQUEST_METHOD"];
        $this->url = $this->parseURL();
    }

    private function parseURL()
    {
        $query = $_SERVER["QUERY_STRING"] ?? "";

        $url = str_replace("?{$query}", "", $_SERVER["REQUEST_URI"]);

        return strlen($url) < 2 ? "/" : rtrim($url, "/");
    }

    public function dispatch()
    {
        $controllers = $this->requests[$this->request_method] ?? [];

        if(empty($controllers)) return;

        $data = $controllers[$this->url] ?? $this->tryGet($controllers);

        $handler = $data["handler"];
        $params = $data["params"] ?? [];
        $directory = $data["directory"];

        $schema = explode("::", $handler);

        $controller = $schema[0];
        $method = $schema[1];

        $request = new Request($params);
        $response = new Response;

        require_once ROOT . "/app/{$directory}/{$controller}.php";

        call_user_func([new $controller, $method], $request, $response);
    }

    private function tryGet($controllers)
    {
        $piecesURL = explode("/", $this->url);
        $lengthURL = count($piecesURL);

        foreach($controllers as $route => $data) {

            if(empty($data["variables"])) continue;

            $piecesRoute = explode("/", $route);

            if($lengthURL != count($piecesRoute)) continue;

            $diff = array_diff_assoc($piecesURL, $piecesRoute);
            $vars = $data["variables"];

            if(count($diff) != count($vars)) continue;

            return [
                "handler"=> $data["handler"],
                "directory"=> $data["directory"],
                "params"=> array_fill_keys($vars, current($diff))
            ];

        }

        return [
            "handler"=> "ErrorController::index",
            "directory"=> "Controllers",
            "params"=> ["code"=> 404]
        ];
    }

    private function verb(string $route, string $handler, string $verb)
    {
        $this->requests[$verb][$route] = [
            "handler"=> $handler,
            "directory"=> "Controllers",
            "variables"=> $this->extractVars($route)
        ];

        $this->current = ["verb"=> $verb, "route"=> $route];

        return $this;
    }

    private function extractVars(string $route): array
    {
        $vars = explode("{", $route);

        if(count($vars) === 1) return [];

        unset($vars[0]);

        $names = array_map(function($var) {
            $index = strpos($var, "}");
            return substr($var, 0, $index);
        }, $vars);

        return $names;
    }

    public function dir(string $directory)
    {
        ["verb"=> $verb, "route"=> $route] = $this->current;
        $this->requests[$verb][$route]["directory"] = $directory;
    }

    public function get(string $route, string $handler)
    {
        return $this->verb($route, $handler, "GET");
    }

    public function post(string $route, string $handler)
    {
        return $this->verb($route, $handler, "POST");
    }

    public function put(string $route, string $handler)
    {
        return $this->verb($route, $handler, "PUT");
    }

    public function delete(string $route, string $handler)
    {
        return $this->verb($route, $handler, "DELETE");
    }

}