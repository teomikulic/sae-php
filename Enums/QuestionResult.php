<?php

namespace Enums;

enum QuestionResult{
    case Success;
    case UknownQuiz;
    case QuestionFormat;
    case UknownQuestionType;
    case RightAnswerFormat;
    case AnswerFormat;
}