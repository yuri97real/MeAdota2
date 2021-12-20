<?php

namespace App\Core;

use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;

abstract class Model {

    private $capsule;

    public function __construct()
    {
        try {

            $this->setCapsule();

        } catch(Exception $e) {

            die( $e->getMessage() );

        }
    }

    private function setCapsule()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver' => DB_CONFIG["driver"],
            'host' => DB_CONFIG["host"],
            'database' => DB_CONFIG["dbname"],
            'username' => DB_CONFIG["username"],
            'password' => DB_CONFIG["password"],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $this->capsule->setAsGlobal();
    }

    public function getCapsule()
    {
        return $this->capsule;
    }

}