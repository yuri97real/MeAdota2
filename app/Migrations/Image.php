<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class Image extends Model {

    public function up()
    {
        $capsule = $this->getCapsule();

        $capsule::schema()->create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->integer('pet_id')->unsigned();

            $table
                ->foreign('pet_id')
                ->references('id')
                ->on('pets')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        $capsule = $this->getCapsule();
        $capsule::schema()->dropIfExists('images');
    }

}