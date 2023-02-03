<?php

namespace Enums;

enum RegistrationResult{
    case Success;
    case EmailFormat;
    case EmailExists;
    case PasswordFormat;
    case PasswordsDifferent;
    case SpecialCharsInNames;
}