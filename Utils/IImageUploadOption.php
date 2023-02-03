<?php

namespace Utils;

use GdImage;

interface IImageUploadOption{
    public function execute(GdImage $image) : GdImage; 
}