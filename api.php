<?php
use Managers\QuizManager;

require_once "Libs/requirements.php";

if(!empty($_GET['id'])){
	$question = QuizManager::getQuiz($_GET['id']);
	if($question)
		echo json_encode($question);
}