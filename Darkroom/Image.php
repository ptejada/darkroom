<?php

namespace Darkroom;

/**
 * Class Image
 *
 * @package Darkroom
 */
class Image
{
    /** @var File The original file reference */
    protected $file;
    /** @var resource The image resource */
    protected $resource;
    /** @var int The internal image type */
    protected $type;
    /** @var callable The function to render the image */
    protected $renderer;
    /** @var ImageEditor Image editor with recipes */
    protected $imageEditor;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Render image to standard output
     */
    public function render()
    {
        // Run any pending edits
        $this->edit()->apply();

        $resource = $this->resource();
        header('Content-Type: ' . $this->mime());
        call_user_func($this->renderer, $resource);
    }

    /**
     * Render the image to a file
     *
     * @param $filePath
     *
     * @return bool True on success, False on failure
     */
    public function renderTo($filePath)
    {
        // Run any pending edits
        $this->edit()->apply();

        $resource = $this->resource();
        return call_user_func($this->renderer, $resource, $filePath);
    }

    /**
     * The image resource
     *
     * @return resource
     */
    public function &resource()
    {
        if (!is_resource($this->resource)) {
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
        }

        return $this->resource;
    }

    /**
     * Start editing the image
     *
     * @return ImageEditor The image editor interface
     */
    public function edit()
    {
        if (empty($this->imageEditor)) {
            $callback = function ($img){
                if (is_resource($img)) {
                    $this->resource = $img;
                }
            };

            $this->imageEditor = new ImageEditor($this, $callback);
        }

        return $this->imageEditor;
    }

    /**
     * The image with in pixels
     *
     * @return int
     */
    public function width()
    {
        return imagesx($this->resource());
    }

    /**
     * The image width in pixels
     * @return int
     */
    public function height()
    {
        return imagesy($this->resource());
    }

    /**
     * Save a snapshot of the image
     *
     * @return Storage\ImageReference The reference to the image snapshot
     */
    public function save()
    {
        return Editor::saveSnapshot($this);
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

    /**
     * Destroy the image when the object is deleted
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            imagedestroy($this->resource);
        }
    }

    /**
     * The mime type for the image
     *
     * @return string
     */
    protected function mime()
    {
        return image_type_to_mime_type($this->type()) ?: 'application/octet-stream';
    }

    /**
     * Image type
     *
     * @return int
     */
    protected function type()
    {
        $this->resource();
        return $this->type;
    }
}
