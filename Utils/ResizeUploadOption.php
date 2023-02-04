<?php

namespace Utils;

use GdImage;

class ResizeUploadOption implements IImageUploadOption{
    public function __construct(
        private int $width,
        private int $height,
        private int $mode = IMG_NEAREST_NEIGHBOUR
    ){}

    public function execute(GdImage $image) : GdImage{
        return imagescale($image, $this->width, $this->height, $this->mode);
    }
}