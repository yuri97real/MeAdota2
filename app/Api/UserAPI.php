<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\UserModel;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserAPI {

    public function create(iRequest $request, iResponse $response)
    {
        $body = $request->body();
        $body->password = password_hash(
            $body->password, PASSWORD_DEFAULT
        );

        $result = (new UserModel)->insertUser($body);

        $response->json($result);
    }

    public function update(iRequest $request, iResponse $response)
    {
        $body = $request->body();
        $array_body = (array) $body;

        unset(
            $array_body["id"],
            $array_body["created_at"],
            $array_body["updated_at"]
        );

        $jwt = $request->authorization();
        $jwt = str_replace("Bearer ", "", $jwt);

        if(isset( $array_body["password"] )) {

            $array_body["password"] = password_hash(
                $array_body["password"], PASSWORD_DEFAULT
            );

        }

        try {

            $decoded = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));

            $affected = (new UserModel)
                ->updateUser($decoded->id, $array_body);

            $payload = [
                "id"=> $decoded->id,
                "name"=> $body->name ?? $decoded->name,
                "email"=> $body->email ?? $decoded->email,
                "ip"=> $_SERVER["REMOTE_ADDR"],
            ];
    
            $new_jwt = JWT::encode($payload, JWT_KEY, 'HS256');

            $response->json([
                "success"=> true,
                "affected"=> $affected,
                "token"=> $new_jwt,
            ]);

        } catch(Exception $e) {

            $response->status(409)->json([
                "success"=> false,
                "info"=> $e,
            ]);

        }

    }

}