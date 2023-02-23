<?php

use Managers\UserManager;

require_once "Libs/requirements.php";

UserManager::logOut();
header('Location: index.php');