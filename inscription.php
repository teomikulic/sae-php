<?php

use Managers\UserManager;

UserManager::loginRequired(false);

require_once "Libs/requirements.php";

require_once "Imports/templates/header.html";

require_once "Imports/templates/inscription.html";

require_once "Imports/templates/footer.html";