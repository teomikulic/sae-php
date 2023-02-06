<?php

namespace Enums;

enum QuizAddingResult{
    case Success;
    case NameTooShort;
    case DescriptionTooShort;
    case ImageError;
}