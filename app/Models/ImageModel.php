<?php

namespace App\Models;

use App\Core\Model;
use Exception;

class ImageModel extends Model {

    public function getImageByPetID(int $pet_id)
    {
        $capsule = $this->getCapsule();

        return $capsule::table("images")->where([
            "pet_id"=> $pet_id,
        ])->limit(3)->get();
    }

    public function insertImage(string $filename, int $pet_id)
    {
        try {

            $capsule = $this->getCapsule();

            $capsule::table("images")->insert([
                "image"=> $filename,
                "pet_id"=> $pet_id,
            ]);

            return [
                "success"=> true,
                "filename"=> $filename,
            ];

        } catch(Exception $e) {

            return [
                "success"=> false,
                "info"=> $e,
            ];

        }
    }

}