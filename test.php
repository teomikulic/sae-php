<?php

use Enums\QuestionType;
require_once "Libs/requirements.php";
echo "". (QuestionType::MultipleChoices->value);

if(!empty($_FILES['file'])){
    $fileNameSplitted = explode(".", $_FILES['file']['name']);
    echo end($fileNameSplitted);
}
?>

<form method="post" enctype="multipart/form-data"><input name="file" type="file"><button type="submit">Test</button></form>