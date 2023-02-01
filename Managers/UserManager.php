<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\RegistrationResult;
use Models\User;

class UserManager{
    const mailRegex = "^[a-z_\\-0-9]+@[a-z\\-0-9]+\\.[a-z]+$";
    const passwordRegex = "(?=^[A-Za-z0-9]{3,24}$)(?=.+[A-Z])(?=.+[0-9])";
    const letterRegex = "[A-z-]+";

    public static function register(DatabaseAccessor $db, string $email, string $password, string $passwordCheck, string $firstName, string $lastName) : RegistrationResult{
        $result = RegistrationResult::Success;

        if (preg_match(self::mailRegex, $email)){
            if(preg_match(self::passwordRegex, $password)){
                if($password == $passwordCheck){
                    if(preg_match(self::letterRegex, $firstName) && preg_match(self::letterRegex, $lastName)){
                        $db->add(new User($email, hash("sha512", $password), $firstName, $lastName, null))
                            ->commit();
                    }
                    else 
                        $result = RegistrationResult::SpecialCharsInNames;
                }
                else
                    $result = RegistrationResult::PasswordsDifferent;
            }
            else
                $result = RegistrationResult::PasswordFormat;
        }
        else
            $result = RegistrationResult::EmailFormat;

        return $result;
    }
}