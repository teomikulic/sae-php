<?php

namespace Managers;

use Enums\FileExtension;
use Enums\UploadType;
use GdImage;

class FileManager{
    const imgWidthIndex = 0;
    const imgHeightIndex = 1;
    const quizIllusPath = "Imports/img/uploads/quiz/";
    const userIllusPath = "Imports/img/uploads/users/";

    private static function isCorrectFileType(mixed $file, array $allowedFileTypes) : bool{
        $fileType = strtolower(mime_content_type($file["tmp_name"]));

        foreach($allowedFileTypes as $allowedFileType){
            if(strpos($fileType, $allowedFileType->value) !== false)
                return true;
        }

        return false;
    }

    private static function convertToImage(mixed $file, string $fileExtension) : GdImage|false{
        return match($fileExtension){
            "jpeg" || "jpg" => imagecreatefromjpeg($file['tmp_name']),
            "png" => imagecreatefrompng($file['tmp_name']),
            default => false
        };
    }

    private static function uploadGdImage(GdImage $image, string $imageExtension, string $uploadPath, string $fileName) : bool{
        $result = false;

        switch($imageExtension){
            case FileExtension::JPEG->value:
            case FileExtension::JPG->value:
                $result = imagejpeg($image, $uploadPath . $fileName);
                break;

            case FileExtension::PNG->value:
                imagealphablending($image, false);
                imagesavealpha($image, true);
                $result = imagepng($image, $uploadPath . $fileName);
                break;
        }

        return $result;
    }

    private static function createPath($path) : void{
        $str = explode("/", $path);
        $dir = "";
        foreach($str as $direc){
            $dir .= $direc ."/";
            if(!is_dir($dir)){
                mkdir($dir, 0777, true);
            }
        }
    }
    
    public static function getUploadPath(UploadType $uploadType) : string|false{
        return  __DIR__ .'/../'. match($uploadType){
            UploadType::Quiz => self::quizIllusPath,
            UploadType::User => self::userIllusPath . (UserManager::isConnected() ? $_SESSION['id'] .'/' : ""),
            default => false
        };
    }

    public static function uploadImage(UploadType $uploadType, mixed $file, array $allowedFileTypes = [], array $fileUploadOptions = []) : bool{
        $result = false;

        $uploadPath = self::getUploadPath($uploadType);
        if($uploadPath !== false){
            self::createPath($uploadPath);

            if(!empty($fileUploadOptions)){
                if(empty($allowedFileTypes) || self::isCorrectFileType($file, $allowedFileTypes)){
                    $fileNameSplitted = explode(".", $file['name']);
                    $fileExtension = strtolower(end($fileNameSplitted));
    
                    $img = self::convertToImage($file, $fileExtension);
    
                    if($img !== false){
                        foreach($fileUploadOptions as $option)
                            $img = $option->execute($img);
    
                        $result = self::uploadGdImage($img, $fileExtension, $uploadPath, basename($file['name']));
                    }
                }
            }
            else
                $result = move_uploaded_file($file['tmp_name'], $uploadPath . basename($file['name']));
        }

        return $result;
    }
}