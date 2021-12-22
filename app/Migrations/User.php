<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class User extends Model {

    public function up()
    {
        $capsule = $this->getCapsule();

        $capsule::schema()->create('users', function (Blueprint $table) {
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
        $capsule = $this->getCapsule();
        $capsule::schema()->dropIfExists('users');
    }

}