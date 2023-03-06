<?php

use BubbleORM\DatabaseAccessor;
use BubbleORM\Enums\DatabaseCreationMode;
use Managers\UserManager;

define("siteName", "OpenQuizz");

// DB
define("dbHost", "servinfo-mariadb");
define("dbUser", "mikulic");
define("dbPassword", "mikulic");
define("dbName", "DBmikulic");

session_start();
$db = new DatabaseAccessor(dbHost, dbUser, dbPassword, dbName, DatabaseCreationMode::Create);
if(isset($_COOKIE["token"]))
    UserManager::autoLogin($db, $_COOKIE["token"]);