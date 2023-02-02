<?php

namespace Enums;

enum QuestionType : int{
    case UserInput = 1;
    case SingleChoice = 2;
    case MultipleChoices = 3;
}