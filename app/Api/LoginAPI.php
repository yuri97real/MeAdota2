<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\UserModel;

use Firebase\JWT\JWT;

class LoginAPI {

    public function auth(iRequest $request, iResponse $response)
    {
        $body = $request->body();

        $genericErrorMessage = [
            "success"=> false,
            "message"=> "Usuário e/ou senha inválidos!",
        ];

        $user = (new UserModel)->getUserBy("email", $body->email, true);

        if(!$user) $response->json($genericErrorMessage);

        $equals = password_verify($body->password, $user->password);

        if(!$equals) $response->json($genericErrorMessage);

        $payload = [
            "id"=> $user->id,
            "name"=> $user->name,
            "email"=> $user->email,
            "ip"=> $_SERVER["REMOTE_ADDR"],
        ];

        $jwt = JWT::encode($payload, JWT_KEY, 'HS256');

        $response->json([
            "success"=> true,
            "token"=> $jwt,
        ]);
    }

}