<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class Image {

    private $schema;

    public function __construct()
    {
        $capsule = (new Model)->getCapsule();
        $this->schema = $capsule::schema();
    }

    public function up()
    {
        $this->schema->create('images', function (Blueprint $table) {
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
        $this->schema->dropIfExists('images');
    }

}