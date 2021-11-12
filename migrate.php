<?php

require_once "vendor/autoload.php";
require_once "config.php";

use App\Migrations\User;
use App\Migrations\Pet;
use App\Migrations\Image;

$options = getopt('', [
    "table:", "method:"
]);

$migrations = [
    "users"=> [
        "up"=> function() {
            call_user_func([new User, "up"]);
        },
        "down"=> function() {
            call_user_func([new User, "down"]);
        }
    ],
    "pets"=> [
        "up"=> function() {
            call_user_func([new Pet, "up"]);
        },
        "down"=> function() {
            call_user_func([new Pet, "down"]);
        }
    ],
    "images"=> [
        "up"=> function() {
            call_user_func([new Image, "up"]);
        },
        "down"=> function() {
            call_user_func([new Image, "down"]);
        }
    ]
];

extract($options);

if(!isset($migrations[$table])) {
    die("Invalid Table!");
}

if(!isset($migrations[$table][$method])) {
    die("Invalid Method!");
}

$migrations[$table][$method]();

echo "Table '{$table}' is {$method}!";