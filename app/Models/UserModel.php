<?php

namespace App\Models;

use App\Core\Model;
use Exception;

class UserModel extends Model {

    public function insertUser($user)
    {
        $capsule = $this->getCapsule();

        try {

            $capsule::table("users")->insert([
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

    public function updateUser(int $user_id, array $values)
    {
        $capsule = $this->getCapsule();

        $affected = $capsule->table('users')
            ->where(["id"=> $user_id])
            ->update($values);

        return $affected;
    }

    public function deleteUser(int $user_id)
    {
        $capsule = $this->getCapsule();

        $deleted = $capsule->table("users")
            ->where(["id"=> $user_id])
            ->delete();

        return $deleted;
    }

}