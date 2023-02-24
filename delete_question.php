<?php

use Managers\UserManager;
use Models\Question;

require_once "Libs/requirements.php";

UserManager::loginRequired(true);

if(!$_SESSION['isAdmin'])
    header('Location: index.php');
else{
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $question = $db->createQuery(Question::class)
            ->where(fn($q) => $q->id == $_GET['id'])
            ->firstOrDefault();
        
        if($question)
            $db->remove($question)->commit();
        
        header('Location: manage_quiz.php');
    }
}