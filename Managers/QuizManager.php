<?php

namespace Managers;

use BubbleORM\DatabaseAccessor;
use Enums\FileType;
use Enums\QuestionResult;
use Enums\QuestionType;
use Enums\QuizAddingResult;
use Enums\UploadType;
use Models\Question;
use Models\Quiz;
use Utils\CropUploadOption;
use Utils\ResizeUploadOption;

class QuizManager{
    const questionRegex = "/[A-z-?!,;.0-9 ]{3,120}/";
    const answerRegex = "/^(?:[A-z-,.0-9+!-*\/' ;]+|\d+)$/";
    const answersSeparator = "|";
    const quizNameMinimumLength = 3;
    const quizDescriptionMinimumLength = 15;
    const quizImgWidth = 300;
    const quizImgHeight = 200;
    const maxShowedDexcriptionLength = 100;

    private static function assignQuestions(DatabaseAccessor $db, Quiz $quiz) : void{
        $quiz->setQuestions($db->createQuery(Question::class)
            ->where(fn($question) => $question->quizId == $quiz->id)
            ->all());
    }

    public static function getQuizzes(DatabaseAccessor $db, ?callable $func = null) : array{
        $query = $db->createQuery(Quiz::class);

        if($func)
            $query->where($func);

        foreach($query->all() as $quiz)
            self::assignQuestions($db, $quiz);

        return $query->all();
    }

    public static function getQuiz(DatabaseAccessor $db, int $quizId) : ?Quiz{
        $quiz = $db->createQuery(Quiz::class)
            ->where(fn($quiz) => $quiz->id == $quizId)
            ->firstOrDefault();
        
        if($quiz) // If quiz isn't null then we load the questions. Because I havn't finish my ORM :'(
            self::assignQuestions($db, $quiz);

        return $quiz;
    }

    public static function deleteQuiz(DatabaseAccessor $db, int $quizId) : bool{
        $result = false;
        $quiz = self::getQuiz($db, $quizId);

        if($quiz){
            $quiz->delete($db);
            $result = true;
        }

        return $result;
    }

    public static function checkAndValidateQuiz(DatabaseAccessor $db, string $name, string $description, mixed $img, ?Quiz $quiz = null) : QuizAddingResult{
        $result = QuizAddingResult::Success;

        if(strlen($name) >= self::quizNameMinimumLength){
            if(strlen($description) >= self::quizDescriptionMinimumLength)
                if((is_null($img) && $quiz) || FileManager::uploadImage(UploadType::Quiz, $img, [FileType::JPG, FileType::PNG],
                    [new CropUploadOption(), new ResizeUploadOption(self::quizImgWidth, self::quizImgHeight)])){
                        if($quiz){
                            $quiz->name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                            $quiz->description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);

                            if($img){
                                $path = FileManager::getUploadPath(UploadType::Quiz);
                                if($path){
                                    unlink($path . $quiz->imgName);
                                    $quiz->imgName = basename($img['name']);
                                }
                            }
                        }
                        else
                            $quiz = new Quiz(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS), filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS),
                                basename($img['name']));
                        
                        $db->add($quiz)->commit();
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

    public static function checkAndValidateQuestion(DatabaseAccessor $db, int $quizId, string $question, int $questionType, string $rightAnswer, array $answers,
        ?Question $quest = null) : QuestionResult{
        $result = QuestionResult::Success;

        if(!is_null($db->createQuery(Quiz::class)->where(fn($x) => $x->id == $quizId)->firstOrDefault())){
            if(preg_match(self::questionRegex, $question)){
                if(!is_null(QuestionType::tryFrom($questionType))){
                    if(preg_match(self::answerRegex, $rightAnswer)){
                        foreach($answers as $answer){
                            if(!empty($answer))
                                if(!is_string($answer) || !preg_match(self::answerRegex, $answer)){
                                    $result = QuestionResult::AnswerFormat;
                                    break;
                                }
                        }

                        if($result == QuestionResult::Success){
                            $answersText = implode(self::answersSeparator, $answers);

                            if($quest){
                                $quest->question = $question;
                                $quest->type = $questionType;
                                $quest->rightAnswer = filter_var($rightAnswer, FILTER_SANITIZE_SPECIAL_CHARS);
                                $quest->answersCSV = filter_var($answersText, FILTER_SANITIZE_SPECIAL_CHARS);
                            }
                            else
                                $quest = new Question($quizId, $question, $questionType, filter_var($rightAnswer, FILTER_SANITIZE_SPECIAL_CHARS), filter_var($answersText, FILTER_SANITIZE_SPECIAL_CHARS));

                            $db->add($quest)->commit();
                        }
                    }
                    else
                        $result = QuestionResult::RightAnswerFormat;
                }
                else
                    $result = QuestionResult::UknownQuestionType;
            }
            else
                $result = QuestionResult::QuestionFormat;
        }
        else
            $result = QuestionResult::UknownQuiz;

        return $result;
    }
}