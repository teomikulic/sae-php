<?php

namespace Enums;

enum RegistrationResult{
    case Success;
    case EmailFormat;
    case PasswordFormat;
    case PasswordsDifferent;
}