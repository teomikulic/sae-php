<?php

use BubbleORM\DatabaseAccessor;

$siteName = "Quiz";

// DB
$dbHost = "127.0.0.1";
$dbUser = "root";
$dbPassword = "";
$dbName = "ecole";


// DO NOT TOUCH
$db = new DatabaseAccessor($dbHost, $dbUser, $dbPassword, $dbName);