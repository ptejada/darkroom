<?php

namespace Darkroom;

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
        // TODO: Optimize this process use mime detection instead
        $ext = strtolower($this->file->extension());
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $this->resource = imagecreatefromjpeg($this->file->filePath());
                $this->type     = IMAGETYPE_JPEG;
                $this->renderer = 'imagejpeg';
                break;
            case 'png':
                $this->resource = imagecreatefrompng($this->file->filePath());
                $this->type     = IMAGETYPE_PNG;
                $this->renderer = 'imagepng';
                break;
            case 'gif':
                $this->resource = imagecreatefromgif($this->file->filePath());
                $this->type     = IMAGETYPE_GIF;
                $this->renderer = 'imagegif';
                break;
            default:
                // TODO: Handle unsupported image type
                break;
        }

        parent::__construct($this->resource);
    }

    /**
     * Save a snapshot of the image
     *
     * @param string $altPath Optional name path
     * @return File
     */
    public function save($altPath = null)
    {
        return Editor::saveAs($this, $altPath);
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
