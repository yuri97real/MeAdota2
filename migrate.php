<?php

require_once "vendor/autoload.php";
require_once "config.php";

use App\Migrations\User;
use App\Migrations\Pet;

$options = getopt('', [
    "table:", "method:"
]);

$migrations = [
    "user"=> [
        "up"=> function() {
            call_user_func([new User, "up"]);
        },
        "down"=> function() {
            call_user_func([new User, "down"]);
        }
    ],
    "pet"=> [
        "up"=> function() {
            call_user_func([new Pet, "up"]);
        },
        "down"=> function() {
            call_user_func([new Pet, "down"]);
        }
    ]
];

extract($options);

if(!isset($migrations[$table][$method])) {
    die("Invalid Method!");
}

$migrations[$table][$method]();

echo "{$table} is {$method}!";