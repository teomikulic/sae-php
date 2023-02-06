<?php

namespace Enums;

enum ConnectionResult{
    case Success;
    case EmailFormat;
    case PasswordFormat;
}