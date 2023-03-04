<?php

use BubbleORM\DatabaseAccessor;
use BubbleORM\Enums\DatabaseCreationMode;
use Managers\UserManager;

define("siteName", "OpenQuizz");

// DB
define("dbHost", "127.0.0.1");
define("dbUser", "root");
define("dbPassword", "");
define("dbName", "saephp");

session_start();
$db = new DatabaseAccessor(dbHost, dbUser, dbPassword, dbName, DatabaseCreationMode::Create);
if(isset($_COOKIE["token"]))
    UserManager::autoLogin($db, $_COOKIE["token"]);