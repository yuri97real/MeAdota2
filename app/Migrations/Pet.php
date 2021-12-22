<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class Pet extends Model {

    public function up()
    {
        $capsule = $this->getCapsule();

        $capsule::schema()->create('pets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('birth');
            $table->string('species');
            $table->integer('owner_id')->unsigned();

            $table
                ->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = $this->getCapsule();
        $capsule::schema()->dropIfExists('pets');
    }

}