<?php

use App\Core\iRequest;
use App\Core\iResponse;
use App\Core\Model;
//use Firebase\JWT\JWT;

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

}