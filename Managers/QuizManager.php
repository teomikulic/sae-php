<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Models\Quiz;

class QuizManager{
    private static function assignQuestions(DatabaseAccessor $db, Quiz $quiz) : void{
        $quiz->setQuestions($db->createQuery(Question::class)
            ->where(fn($question) => $question->quizId == $quiz->id)
            ->all());
    }

    public static function getQuiz(DatabaseAccessor $db, int $quizId) : ?Quiz{
        $quiz = $db->createQuery(Quiz::class)
            ->where(fn($quiz) => $quiz->id == $quizId)
            ->firstOrDefault();
        
        if($quiz) // If quiz isn't null then we load the questions. Because I havn't finish my ORM :'(
            self::assignQuestions($db, $quiz);

        return $quiz;
    }
}