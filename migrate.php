<?php

require_once "vendor/autoload.php";
require_once "config.php";

use App\Migrations\User;
use App\Migrations\Pet;
use App\Migrations\Image;

$options = getopt('', [
    "method:",
]);

$migrations = [
    "users"=> new User,
    "pets"=> new Pet,
    "images"=> new Image,
];

extract($options);

if(!in_array($method, ["up", "down"])) {
    die("Method is not valid!");
}

if($method == "down") {
    $migrations = array_reverse($migrations);
}

foreach($migrations as $migration) {

    call_user_func([$migration, $method]);

}