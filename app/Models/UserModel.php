<?php

namespace App\Models;

use App\Core\Model;
use Exception;

class UserModel extends Model {

    public function insertUser($user)
    {
        $capsule = $this->getCapsule();

        try {

            $capsule::table('users')->insert([
                "name"=> $user->name,
                "email"=> $user->email,
                "password"=> $user->password,
            ]);

            return ["success"=> true];

        } catch(Exception $e) {

            return [
                "success"=> false,
                "info"=> $e,
            ];

        }
    }

    public function getUserBy(string $column, string $value, bool $needPass = false)
    {
        $capsule = $this->getCapsule();

        $fields = [
            "id", "name", "email", "profile",
        ];

        if($needPass) {
            array_push($fields, "password");
        }

        return $capsule::table("users")->where([
            $column=> $value
        ])->first($fields);
    }

}