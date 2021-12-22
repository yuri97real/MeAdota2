<?php

namespace App\Models;

use App\Core\Model;
use Exception;

class PetModel extends Model {

    public function insertPet($owner_id, $pet)
    {
        $capsule = $this->getCapsule();

        try {

            $capsule::table('pets')->insert([
                "name"=> $pet->name,
                "birth"=> $pet->birth,
                "species"=> $pet->species,
                "owner_id"=> $owner_id,
            ]);

            return ["success"=> true];

        } catch(Exception $e) {

            return [
                "success"=> false,
                "info"=> $e,
            ];

        }
    }

    public function selectPets(int $offset, int $limit)
    {
        $capsule = $this->getCapsule();

        return $capsule::table('pets')
                ->skip($offset)
                ->take($limit)
                ->orderBy("id", "desc")
                ->get();
    }

    public function getPetByID(int $pet_id)
    {
        $capsule = $this->getCapsule();

        return $capsule::table("pets")->where([
            "id"=> $pet_id,
        ])->first();
    }

    public function getPetsByOwnerID(int $owner_id)
    {
        $capsule = $this->getCapsule();

        return $capsule::table("pets")->where([
            "owner_id"=> $owner_id,
        ])->take(10)->get();
    }

}