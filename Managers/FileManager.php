<?php

namespace Managers;

use Enums\FileExtension;
use Enums\UploadType;
use GdImage;

class FileManager{
    const imgWidthIndex = 0;
    const imgHeightIndex = 1;
    const quizIllusPath = __DIR__."../Imports/img/uploads/quiz/";
    const userIllusPath = __DIR__."../Imports/img/uploads/users/";

    private static function getUploadPath(UploadType $uploadType) : string|false{
        return match($uploadType){
            UploadType::Quiz => self::quizIllusPath,
            UploadType::User => self::userIllusPath,
            default => false
        };
    }

    private static function isCorrectFileType(mixed $file, array $allowedFileTypes) : bool{
        $fileType = strtolower(mime_content_type($file["tmp_name"]));

        foreach($allowedFileTypes as $allowedFileType){
            if(strpos($fileType, $allowedFileType->value) !== false)
                return true;
        }

        return false;
    }

    private static function cropImgSquare(GdImage $img) : GdImage{
        
    }
    
    private static function resizeImg(GdImage $img, int $width, int $height) : GdImage{
        return imagescale($img, $width, $height, IMG_NEAREST_NEIGHBOUR);
    }

    private static function convertToImage(string $fileExtension) : GdImage|false{
        return match($fileExtension){
            "jpeg" || "jpg" => imagecreatefromjpeg($file['tmp_name']),
            "png" => imagecreatefrompng($file['tmp_name']),
            default => false
        };
    }

    private static function handleUploadOption(GdImage $image, ImageUploadOption $option) : GdImage|false{
        return match($option) {
            ImageUploadOption::Crop => cropImgSquare($image),
            ImageUploadOption::Resize => resizeImg(), // TO DO

        }
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

    public static function uploadImage(UploadType $uploadType, mixed $file, array $allowedFileTypes = [], array $fileUploadOptions = []) : bool{
        $result = false;

        $uploadPath = self::getUploadPath($uploadType);
        if($uploadPath !== false){
            if(!empty($fileUploadOptions)){
                // TO DO isCorrectFileType
                $fileNameSplitted = explode(".", $file['name']);
                $fileExtension = strtolower(end($fileNameSplitted));

                $img = self::convertToImage($fileExtension);

                if($img !== false){
                    foreach($fileUploadOptions as $option)
                        $img = /* TO DO action*/;

                    $result = self::uploadGdImage($img, $fileExtension, $uploadPath, basename($file['name']));
                }
            }
            else
                $result = move_uploaded_file($file['tmp_name'], $uploadPath . basename($file['name']));
        }

        return $result;
    }
}