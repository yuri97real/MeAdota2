<?php

use App\Core\iRequest;
use App\Core\iResponse;
use App\Core\Model;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ImageAPI {

    public function create(iRequest $req, iResponse $res)
    {
        $jwt = $req->authorization();
        $jwt = str_replace("Bearer ", "", $jwt);

        try {

            JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));

            if(!isset($_FILES["images"])) $res->json([
                "success"=> false,
                "message"=> "File Not Uploaded!"
            ]);

            $pet_id = $req->params()->pet_id;
            $filename = $this->cutAndSaveImage();

            $capsule = (new Model)->getCapsule();

            $capsule::table("images")->insert([
                "image"=> $filename,
                "pet_id"=> $pet_id
            ]);

            $res->json([
                "filename"=> $filename
            ]);

        } catch(Exception $e) {

            $res->status(409)->json([
                "success"=> false,
                "info"=> $e
            ]);

        }
    }

    private function cutAndSaveImage()
    {
        $imagine = new Imagine\Gd\Imagine();

        $size = new Imagine\Image\Box(500, 500);

        $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $filename = date("d_m_y_H_i_s") . ".jpeg";

        $imagine->open($_FILES['images']['tmp_name'])
                ->thumbnail($size, $mode)
                ->save(ROOT . '/public/images/uploads/' . $filename)
        ;

        return $filename;
    }

}