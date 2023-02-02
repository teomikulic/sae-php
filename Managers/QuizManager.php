<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\QuizAddingResult;
use Models\Question;
use Models\Quiz;

class QuizManager{
    const quizNameMinimumLength = 3;
    const quizDescriptionMinimumLength = 15;

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

    public static function createQuizz(DatabaseAccessor $db, string $name, string $description, string $imgName) : QuizAddingResult{
        $result = QuizAddingResult::Success;

        if(strlen($name) >= self::quizNameMinimumLength){
            if(strlen($description) >= self::quizDescriptionMinimumLength)
                // Upload image
                $db->add(new Quiz(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS), filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS),
                $imgName))
                ->commit();
            else
                $result = QuizAddingResult::DescriptionTooShort;
        }
        else
            $result = QuizAddingResult::NameTooShort;

        return $result;
    }
}