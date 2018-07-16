<?php

namespace Darkroom\Storage;

use Darkroom\Image;

class PathScheme
{
    /**
     * PathScheme constructor.
     */
    public function __construct(){
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Generates path for a new snapshot
     *
     * @param Image $image The image reference
     *
     * @return string
     */
    public function snapshot(Image $image)
    {
        $directory = $image->file()->directory() . DIRECTORY_SEPARATOR . $image->file()->name();
        $filename  = (new \DateTime())->format('Y-m-d-Hisu') . '.' . $image->file()->extension();

        return $directory . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Generates the base URL for public image URLs
     *
     * @return string
     */
    protected function baseUrl()
    {
        // TODO: this is the default base URL generator, make this option configurable
        return $this->baseUrl ?: 'http://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Sets the base URL for public URLs
     *
     * @param $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Generates an image public URL
     *
     * @param Image $image The image reference
     *
     * @return string The public url
     */
    public function url(Image $image)
    {
        return $this->baseUrl() . $image->file()->filePath();
    }
}
