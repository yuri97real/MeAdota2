<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class User {

    private $schema;

    public function __construct()
    {
        $capsule = (new Model)->getCapsule();
        $this->schema = $capsule::schema();
    }

    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('users');
    }

}