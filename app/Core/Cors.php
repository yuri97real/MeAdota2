<?php

namespace App\Core;

class Cors {

    private $allowed_methods = ["GET", "POST"], $allowed_domains = [];

    public function use()
    {
        $methods = implode(", ", $this->allowed_methods);

        header("Access-Control-Allow-Methods: {$methods}");
        
        $http_origin = $_SERVER['HTTP_ORIGIN'] ?? "";
        $allowed = in_array($http_origin, $this->allowed_domains);

        if(!$allowed) return;
        
        header("Access-Control-Allow-Origin: {$http_origin}");
    }

    public function setMethods(array $allowed_methods)
    {
        if(empty($allowed_methods)) return;
        $this->allowed_methods = $allowed_methods;
    }

    public function setDomains(array $allowed_domains)
    {
        $this->allowed_domains = $allowed_domains;
    }

}