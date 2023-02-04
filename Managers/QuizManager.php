<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\FileType;
use Enums\QuizAddingResult;
use Enums\UploadType;
use Models\Question;
use Models\Quiz;
use Utils\CropUploadOption;
use Utils\ResizeUploadOption;

class QuizManager{
    const quizNameMinimumLength = 3;
    const quizDescriptionMinimumLength = 15;
    const quizImgWidth = 300;
    const quizImgHeight = 200;

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

    public static function createQuizz(DatabaseAccessor $db, string $name, string $description, mixed $img) : QuizAddingResult{
        $result = QuizAddingResult::Success;

        if(strlen($name) >= self::quizNameMinimumLength){
            if(strlen($description) >= self::quizDescriptionMinimumLength)
                if(FileManager::uploadImage(UploadType::Quiz, $img, [FileType::JPG, FileType::PNG],
                    [new CropUploadOption(), new ResizeUploadOption(self::quizImgWidth, self::quizImgHeight)])){
                        $db->add(new Quiz(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS), filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS),
                        basename($img['name'])))
                        ->commit();
                    }
                    else
                        $result = QuizAddingResult::ImageError;
            else
                $result = QuizAddingResult::DescriptionTooShort;
        }
        else
            $result = QuizAddingResult::NameTooShort;

        return $result;
    }
}