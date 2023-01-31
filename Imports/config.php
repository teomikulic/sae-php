<?php

use BubbleORM\DatabaseAccessor;
use BubbleORM\Enums\DatabaseCreationMode;
use Models\Question;

$siteName = "OpenQuizz";

// DB
$dbHost = "127.0.0.1";
$dbUser = "root";
$dbPassword = "";
$dbName = "ecole";


// DO NOT TOUCH
$db = new DatabaseAccessor($dbHost, $dbUser, $dbPassword, $dbName, DatabaseCreationMode::Override);

function createModels(){
    global $db;
    
    $db->createQuery(Quiz::class)->firstOrDefault();
    $db->createQuery(Question::class)->firstOrDefault();
    $db->createQuery(User::class)->firstOrDefault();
}