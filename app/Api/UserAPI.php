<?php

use App\Core\iRequest;
use App\Core\iResponse;
use App\Core\Model;

class UserAPI {

    public function create(iRequest $req, iResponse $res)
    {
        $name = $req->body()->name;
        $email = $req->body()->email;
        $password = $req->body()->password;

        $password = password_hash($password, PASSWORD_DEFAULT);

        $capsule = (new Model)->getCapsule();

        try {
            $capsule::table('users')->insert([
                "name"=> $name,
                "email"=> $email,
                "password"=> $password
            ]);
    
            $res->json([
                "success"=> true
            ]);
        } catch(Exception $e) {
            $res->json([
                "success"=> false,
                "info"=> $e
            ]);
        }
    }

}