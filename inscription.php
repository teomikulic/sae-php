<?php

use Managers\UserManager;

require_once "Libs/requirements.php";

UserManager::loginRequired(false);

require_once "Imports/templates/header.html";

require_once "Imports/templates/inscription.html";

require_once "Imports/templates/footer.html";