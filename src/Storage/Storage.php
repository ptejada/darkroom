<?php

namespace Darkroom\Storage;

use Darkroom\Image;

/**
 * Interface Storage defines features for an image store
 *
 * @package Darkroom\Storage
 */
interface Storage
{
    /**
     * Saves an image
     *
     * @param Image  $image   Image reference
     * @param string $altName Alternative file name
     *
     * @return bool|File
     */
    public function save(Image $image, $altName = null);
}
