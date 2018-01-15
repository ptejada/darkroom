<?php

namespace Darkroom;

/**
 * Class ResourceImage
 *
 * @package Darkroom
 */
class ImageResource
{
    /** @var resource The image resource */
    protected $resource;
    /** @var int The internal image type */
    protected $type;
    /** @var callable The function to render the image */
    protected $renderer;
    /** @var ImageEditor Image editor with recipes */
    protected $imageEditor;

    /**
     * ImageResource constructor.
     *
     * @param resource $source An image resource
     */
    public function __construct($source)
    {
        if (is_resource($source)) {
            $this->resource = $source;
            $this->type     = $this->type ?: IMAGETYPE_PNG;
            $this->renderer = $this->renderer ?: 'imagepng';
        } else {
            throw new \InvalidArgumentException('Argument 1 passed to ' . __METHOD__ . ' must be a resource, ' . gettype($source) . 'given.');
        }
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
        return call_user_func($this->renderer, $resource, $filePath . '.' . $this->extension());
    }

    /**
     * Image file type extension
     *
     * @return string
     */
    protected function extension()
    {
        return image_type_to_extension($this->type());
    }

    /**
     * The image resource
     *
     * @return resource
     */
    public function resource()
    {
        return $this->resource;
    }

    /**
     * Detach the internal GD resource from the object
     * Note: After detaching this object will most likely become un usable
     *
     * @return resource
     */
    public function detach()
    {
        $resource = $this->resource();
        $this->resource = null;

        return $resource;
    }

    /**
     * Convert the image to different format
     * @param int $imageType
     */
    public function convertTo($imageType)
    {
        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case IMAGETYPE_JPEG:
                $this->type = IMAGETYPE_JPEG;
                $this->renderer = 'imagejpeg';
                break;
            case 'png':
            case IMAGETYPE_PNG:
                $this->type = IMAGETYPE_PNG;
                $this->renderer = 'imagepng';
                break;
            case 'gif':
            case IMAGETYPE_GIF:
                $this->type = IMAGETYPE_GIF;
                $this->renderer = 'imagegif';
                break;
        }
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
        return $this->type;
    }
}
