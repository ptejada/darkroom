<?php

namespace Darkroom;

use Darkroom\Storage\File;

/**
 * Class Image
 *
 * @package Darkroom
 */
class Image extends ImageResource
{
    /** @var File The original file reference */
    protected $file;

    /**
     * Image constructor.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        if (! $file->exists()) {
            throw new \InvalidArgumentException('Cannot open file: ' . $file->filePath());
        }

        $this->mimeString = mime_content_type($file->filePath());

        switch ($this->mimeString) {
            case 'image/jpeg':
                $this->resource = imagecreatefromjpeg($this->file->filePath());
                $this->renderer = 'imagejpeg';
                $this->ext      = 'jpg';
                break;
            case 'image/png':
                $this->resource = imagecreatefrompng($this->file->filePath());
                $this->renderer = 'imagepng';
                $this->ext      = 'png';
                break;
            case 'image/gif':
                $this->resource = imagecreatefromgif($this->file->filePath());
                $this->renderer = 'imagegif';
                $this->ext      = 'gif';
                break;
            default:
                throw new \InvalidArgumentException("File type {$this->mimeString} is not supported.");
                break;
        }

        parent::__construct($this->resource);
    }

    /**
     * Save a snapshot of the image
     *
     * @param string $altPath Optional name path
     *
     * @return File
     */
    public function save($altPath = null)
    {
        return Editor::save($this, $altPath);
    }

    /**
     * The original file reference
     *
     * @return File
     */
    public function file()
    {
        return $this->file;
    }
}
