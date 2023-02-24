<?php

use Managers\UserManager;

require_once "Libs/requirements.php";

UserManager::loginRequired(true);

if(!$_SESSION['isAdmin'])
    header('Location: index.php');
else{
    require_once "Imports/templates/header.html";

    require_once "Imports/templates/add_question.html";

    require_once "Imports/templates/footer.html";
}