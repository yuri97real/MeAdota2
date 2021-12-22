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

            $files = $this->cutAndSaveImage();

            if(empty($files)) $response->json([
                "success"=> false,
                "message"=> "Não foi possível salvar a imagem!",
            ]);

            $result = (new ImageModel)->insertImage($files, $pet_id);

            $response->json($result);

        } catch(Exception $e) {

            $response->status(409)->json([
                "success"=> false,
                "info"=> $e,
            ]);

        }
    }

    private function cutAndSaveImage(): array
    {
        try {

            $imagine = new Imagine\Gd\Imagine();

            $size = new Imagine\Image\Box(500, 500);

            $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

            $reverse_tmp_names = array_slice($_FILES['images']['tmp_name'], 0, 3);
            $reverse_tmp_names = array_reverse($reverse_tmp_names);

            $files = [];

            foreach($reverse_tmp_names as $index => $tmp_name) {

                $filename = date("d_m_y_H_i_s") . "_{$index}.jpg";

                $files[] = $filename;

                $imagine->open($tmp_name)
                        ->thumbnail($size, $mode)
                        ->save(ROOT . '/public/images/uploads/' . $filename);
                
            }

            return $files;

        } catch(Exception $e) {

            return [];

        }

    }

}