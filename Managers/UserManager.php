<?php

namespace Managers;

use Models\User;

class UserManager{
    public static function getUser(callable $f) : User{
        global $db;

        return $db->createQuery(User::class)
            ->where($f)
            ->firstOrDefault();
    }

    public static function getUsers(callable $f) : array{
        global $db;

        return $db->createQuery(User::class)
            ->where($f)
            ->all();
    }

    public static function register(string $email, string $password, string $firstName, string $lastName) : void{
        global $db;

        
    }
}