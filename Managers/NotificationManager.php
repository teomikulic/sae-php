<?php

namespace Managers;

class NotificationManager{
    public static function notifyError(string $message, string $redirectURL = "") : void{
        echo '
            <script>addEventListener("load", () => notify("'. $message .'", "error.png", "'. $redirectURL .'"));</script>
        ';
    }

    public static function notifySuccess(string $message, string $redirectURL = "") : void{
        echo '
            <script>addEventListener("load", () => notify("'. $message .'", "work.png", "'. $redirectURL .'"));</script>
        ';
    }
}