<?php

namespace Darkroom\Storage;

use Darkroom\File;

class ImageReference
{
    protected $baseUrl = '/';
    protected $basePath;

    /**
     * ImageReference constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function publicUrl()
    {
        // TODO: this is the default base URL generator, make this option configurable
        $uriPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->localPath());
        return 'http://' . $_SERVER['HTTP_HOST'] . $uriPath;
    }

    public function localPath()
    {
        return realpath($this->basePath);
    }

    public function file()
    {
        return new File($this->localPath());
    }
}
