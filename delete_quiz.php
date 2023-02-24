<?php

use Managers\QuizManager;
use Managers\UserManager;

require_once "Libs/requirements.php";

UserManager::loginRequired(true);

if(!$_SESSION['isAdmin'])
    header('Location: index.php');
else{
    if(isset($_GET['id']) && !empty($_GET['id'])){
        QuizManager::deleteQuiz($db, $_GET['id']);
        header('Location: manage_quiz.php');
    }
}