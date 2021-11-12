<?php

use App\Core\iRequest;
use App\Core\iResponse;
use App\Core\Model;
use Firebase\JWT\JWT;

class LoginAPI {

    public function auth(iRequest $req, iResponse $res)
    {
        $email = $req->body()->email;
        $password = $req->body()->password;

        $errorDefault = [
            "success"=> false
        ];

        $capsule = (new Model)->getCapsule();

        $user = $capsule::table('users')->where([
            "email"=> $email
        ])->first();

        if(!$user) $res->json($errorDefault);

        $equals = password_verify($password, $user->password);

        if(!$equals) $res->json($errorDefault);

        $payload = [
            "id"=> $user->id,
            "name"=> $user->name,
            "email"=> $user->email,
            "ip"=> $_SERVER["REMOTE_ADDR"]
        ];

        $jwt = JWT::encode($payload, JWT_KEY, 'HS256');

        $res->json([
            "success"=> true,
            "token"=> $jwt
        ]);
    }

}