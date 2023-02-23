<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\ConnectionResult;
use Enums\RegistrationResult;
use Models\User;

class UserManager
{
    const mailRegex = "/^[a-z_\\-0-9]+@[a-z\\-0-9]+\\.[a-z]+$/"; // Regex pour les emails
    const passwordRegex = "/(?=^[A-Za-z0-9-'+!]{3,24}$)(?=.*[A-Z])(?=.*[0-9])/"; // Regex pour les mots de passe
    const letterRegex = "/[A-z-]+/"; // Regex pour les lettres et les tirets

    private static function getUser(DatabaseAccessor $db, callable $func): ?User
    {
        return $db->createQuery(User::class)->where($func)->firstOrDefault(); // Récupération de l'utilisateur dans la base de données
    }

    // NB : Il n'y a pas de filter_var dû aux regex au dessus qui clean déjà
    public static function register(DatabaseAccessor $db, string $email, string $password, string $passwordCheck, string $firstName, string $lastName): RegistrationResult
    {
        $result = RegistrationResult::Success; // Succès par défaut
        if (preg_match(self::mailRegex, $email)) {
            if (is_null(self::getUser($db, fn ($u) => $u->email == $email))) {
                if (preg_match(self::passwordRegex, $password)) {
                    if ($password == $passwordCheck) {
                        if (preg_match(self::letterRegex, $firstName) && preg_match(self::letterRegex, $lastName)) {
                            $db->add(new User($email, hash("sha512", $password), $firstName, $lastName, null, 0))
                                ->commit(); // Ajout de l'utilisateur dans la base de données
                        } else
                            $result = RegistrationResult::SpecialCharsInNames; // Caractères spéciaux dans le nom ou prénom
                    } else
                        $result = RegistrationResult::PasswordsDifferent; // Les mots de passe ne sont pas identiques
                } else
                    $result = RegistrationResult::PasswordFormat; // Format du mot de passe incorrect
            } else
                $result = RegistrationResult::EmailExists; // L'email existe déjà
        } else
            $result = RegistrationResult::EmailFormat; // Format de l'email incorrect

        return $result;
    }

    public static function isConnected() : bool{
        return isset($_SESSION) && !empty($_SESSION["id"]); // Vérification de la présence de l'id de l'utilisateur dans la session
    }

    public static function loginRequired(bool $connected) : void{
        if(($connected && !self::isConnected()) ||
            (!$connected && self::isConnected()))
            header("Location: index.php"); // Redirection vers la page d'accueil
    }

    public static function login(DatabaseAccessor $db, string $email, string $password): ConnectionResult
    {
        $result = ConnectionResult::Success; // Succès par défaut

        if (preg_match(self::mailRegex, $email)) {
            if (preg_match(self::passwordRegex, $password)) {
                $cryptedPassword = hash("sha512", $password); // Cryptage du mot de passe
                $user = self::getUser($db, fn ($u) => $u == $email && $u->password == $cryptedPassword); // Récupération de l'utilisateur dans la base de données
                if(!is_null($user)){

                    // Stockage des données de l'utilisateur dans la session
                    $_SESSION["id"] = $user->id; // id de l'utilisateur
                    $_SESSION["email"] = $user->email;  // email de l'utilisateur
                    $_SESSION["password"] = $user->password; // mot de passe de l'utilisateur
                    $_SESSION["firstName"] = $user->firstName; // prénom de l'utilisateur
                    $_SESSION["lastName"] = $user->lastName; // nom de l'utilisateur
                    $_SESSION["img"] = $user->img; // image de l'utilisateur
                    $_SESSION["isAdmin"] = $user->isAdmin; // booléen indiquant si l'utilisateur est admin ou non
                }
                else
                    $result = ConnectionResult::WrongCredentials; // Mauvais identifiants
            }
            else
                $result = ConnectionResult::PasswordFormat; // Format du mot de passe incorrect
        }
        else
            $result = ConnectionResult::EmailFormat; // Format de l'email incorrect

        return $result;
    }    

    public static function logOut() : void{
        $_SESSION = []; // Suppression des données de la session
        session_destroy(); // Destruction de la session
    }
}
