<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Models\Quiz;

class QuizManager{
    public static function getQuiz(DatabaseAccessor $db, int $quizId) : ?Quiz{
        $quiz = $db->createQuery(Quiz::class)
            ->where(fn($quiz) => $quiz->id == $quizId)
            ->firstOrDefault();
        
        if($quiz) // If quiz isn't null then we load the questions. Because I havn't finish my ORM :'(
            $quiz->questions = $db->createQuery(Question::class)
                ->where(fn($question) => $question->quizId == $quizId)
                ->all();

        return $quiz;
    }
}