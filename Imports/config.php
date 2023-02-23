<?php

use BubbleORM\DatabaseAccessor;
use BubbleORM\Enums\DatabaseCreationMode;

define("siteName", "OpenQuizz");

// DB
define("dbHost", "127.0.0.1");
define("dbUser", "root");
define("dbPassword", "");
define("dbName", "ecole");

session_start();
$db = new DatabaseAccessor(dbHost, dbUser, dbPassword, dbName, DatabaseCreationMode::Create);