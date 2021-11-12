<?php

use App\Core\iRequest;
use App\Core\iResponse;
use App\Core\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PetAPI {

    public function index(iRequest $req, iResponse $res)
    {
        $capsule = (new Model)->getCapsule();

        $page = $req->query()->page ?? 1;
        $page = $page > 0 ? $page : 1;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $pets = $capsule::table('pets')
            ->skip($offset)
            ->take($limit)
            ->get();

        $res->json([
            "pets"=> $pets,
            "page"=> $page
        ]);
    }

    public function create(iRequest $req, iResponse $res)
    {
        $jwt = $req->authorization();
        $jwt = str_replace("Bearer ", "", $jwt);

        try {

            $decoded = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));
            $capsule = (new Model)->getCapsule();

            $capsule::table('pets')->insert([
                "name"=> $req->body()->name,
                "birth"=> $req->body()->birth,
                "species"=> $req->body()->species,
                "owner_id"=> $decoded->id
            ]);

            $res->json([
                "success"=> true
            ]);

        } catch(Exception $e) {

            $res->status(409)->json([
                "success"=> false,
                "info"=> $e
            ]);

        }
    }

}