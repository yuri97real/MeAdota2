<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\ImageModel;
use App\Models\PetModel;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ImageAPI {

    public function create(iRequest $request, iResponse $response)
    {
        $jwt = $request->authorization();
        $jwt = str_replace("Bearer ", "", $jwt);

        try {

            $user = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));

            if(!isset($_FILES["images"])) $response->json([
                "success"=> false,
                "message"=> "Arquivo não selecionado!"
            ]);

            $pet_id = $request->params()->pet_id;
            $pet = (new PetModel)->getPetByID($pet_id);

            if($pet->owner_id != $user->id) $response->json([
                "success"=> false,
                "message"=> "Esse pet não lhe pertence!",
            ]);

            $filename = $this->cutAndSaveImage();

            if($filename == "") $response->json([
                "success"=> false,
                "message"=> "Não foi possível salvar a imagem!",
            ]);

            $result = (new ImageModel)->insertImage($filename, $pet_id);

            $response->json($result);

        } catch(Exception $e) {

            $response->status(409)->json([
                "success"=> false,
                "info"=> $e,
            ]);

        }
    }

    private function cutAndSaveImage(): string
    {
        try {

            $imagine = new Imagine\Gd\Imagine();

            $size = new Imagine\Image\Box(500, 500);

            $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

            $filename = date("d_m_y_H_i_s") . ".jpeg";

            $imagine->open($_FILES['images']['tmp_name'])
                    ->thumbnail($size, $mode)
                    ->save(ROOT . '/public/images/uploads/' . $filename)
            ;

            return $filename;

        } catch(Exception $e) {

            return "";

        }

    }

}