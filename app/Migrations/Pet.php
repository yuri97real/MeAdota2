<?php

namespace App\Migrations;

use App\Core\Model;
use Illuminate\Database\Schema\Blueprint;

class Pet {

    private $schema;

    public function __construct()
    {
        $capsule = (new Model)->getCapsule();
        $this->schema = $capsule::schema();
    }

    public function up()
    {
        $this->schema->create('pets', function (Blueprint $table) {
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
        $this->schema->dropIfExists('pets');
    }

}