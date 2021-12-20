<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\PetModel;
use App\Models\UserModel;
use App\Models\ImageModel;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PetAPI {

    public function index(iRequest $request, iResponse $response)
    {
        $page = $request->query()->page ?? 1;
        $page = $page > 0 ? $page : 1;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $pets = (new PetModel)->selectPets($offset, $limit);

        $response->json([
            "pets"=> $pets,
            "page"=> $page,
        ]);
    }

    public function show(iRequest $request, iResponse $response)
    {
        $pet_id = $request->params()->pet_id;

        $pet = (new PetModel)->getPetByID($pet_id);

        if(!$pet) $response->json([
            "success"=> false,
            "message"=> "Pet Not Found!",
        ]);

        $owner = (new UserModel)->getUserBy("id", $pet->owner_id);

        $images = (new ImageModel)->getImageByPetID($pet_id);

        $response->json([
            "pet"=> $pet,
            "owner"=> $owner,
            "images"=> $images,
        ]);
    }

    public function create(iRequest $request, iResponse $response)
    {
        $jwt = $request->authorization();
        $jwt = str_replace("Bearer ", "", $jwt);

        try {

            $decoded = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));

            $result = (new PetModel)->insertPet($decoded->id, $request->body());

            $response->json($result);

        } catch(Exception $e) {

            $response->status(409)->json([
                "success"=> false,
                "info"=> $e,
            ]);

        }
    }

}