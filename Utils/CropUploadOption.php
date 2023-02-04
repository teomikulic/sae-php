<?php

namespace Utils;

use GdImage;
use Managers\FileManager;

class CropUploadOption implements IImageUploadOption{
    public function execute(GdImage $image) : GdImage{
        $imgDimensions = [imagesx($image), imagesy($image)];
        $shortest = min($imgDimensions);
        $im2 = null;
        
        if($shortest == $imgDimensions[FileManager::imgHeightIndex])
            $im2 = imagecrop($image, ['x' => $imgDimensions[FileManager::imgWidthIndex] / 2 - $shortest / 2, 'y' => 0, 'width' => $shortest, 'height' => $shortest]);
        else
            $im2 = imagecrop($image, ['x' => 0, 'y' => $imgDimensions[FileManager::imgHeightIndex] / 2 - $shortest / 2, 'width' => $shortest, 'height' => $shortest]);
        
        return $im2;
    }
}