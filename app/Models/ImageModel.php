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

    public function insertImage(array $files, int $pet_id)
    {
        try {

            $capsule = $this->getCapsule();

            $images = [];

            foreach($files as $file) {
                $images[] = [
                    "image"=> $file,
                    "pet_id"=> $pet_id,
                ];
            }

            $capsule::table("images")->insert($images);

            return [
                "success"=> true,
                "files"=> $files,
            ];

        } catch(Exception $e) {

            return [
                "success"=> false,
                "info"=> $e,
            ];

        }
    }

}