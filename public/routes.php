<?php

$router = new App\Core\Router;

// error
$router->get("/error", "ErrorController::index");
$router->get("/error/{code}", "ErrorController::index");

// users
$router->post("/user", "UserAPI::create")->dir("Api");
$router->put("/user", "UserAPI::update")->dir("Api");
$router->delete("/user", "UserAPI::destroy")->dir("Api");

// login
$router->post("/login", "LoginAPI::auth")->dir("Api");

// home
$router->get("/", "PetAPI::index")->dir("Api");

// pets
$router->post("/pet", "PetAPI::create")->dir("Api");
$router->get("/pet/{pet_id}", "PetAPI::show")->dir("Api");

// images
$router->post("/image/{pet_id}", "ImageAPI::create")->dir("Api");

$router->dispatch();