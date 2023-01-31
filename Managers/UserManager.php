<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\RegistrationResult;
use Models\User;

class UserManager{
    private static string $mailRegex = "^[a-z_\\-0-9]+@[a-z\\-0-9]+\\.[a-z]+$";

    public static function register(DatabaseAccessor $db, string $email, string $password, string $passwordCheck, string $firstName, string $lastName) : RegistrationResult{
        $result = RegistrationResult::Success;

        if (preg_match(self::$mailRegex, $email)){
            
        }
        else
            $result = RegistrationResult::EmailFormat;

        return $result;
    }
}