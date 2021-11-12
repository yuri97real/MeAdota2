<?php

require_once "../vendor/autoload.php";
require_once "../config.php";

$cors = new App\Core\Cors();

$cors->setMethods([
    "GET", "POST", "PUT", "DELETE"
]);

/*$cors->setDomains([
    "https://www.google.com"
]);*/

$cors->use();

$router = new App\Core\Router;

// error
$router->get("/error", "ErrorController::index");
$router->get("/error/{code}", "ErrorController::index");

// create user
$router->post("/user", "UserAPI::create")->dir("Api");

// login
$router->post("/login", "LoginAPI::auth")->dir("Api");

// home
$router->get("/", "PetAPI::index")->dir("Api");

// pets
$router->post("/pet", "PetAPI::create")->dir("Api");

$router->dispatch();