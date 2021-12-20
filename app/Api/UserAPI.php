<?php

use App\Core\iRequest;
use App\Core\iResponse;

use App\Models\UserModel;

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

}