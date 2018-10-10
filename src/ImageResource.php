<?php

namespace Darkroom;

use Darkroom\Storage\File;
use Darkroom\Utility\BoxInterface;

/**
 * Class ResourceImage
 *
 * @package Darkroom
 */
class ImageResource implements BoxInterface
{
    /** @var resource The image resource */
    protected $resource;
    /** @var string The image type mime string */
    protected $mimeString;
    /** @var string The image extension to use */
    protected $ext;
    /** @var callable The function to render the image */
    protected $renderer;
    /** @var ImageEditor Image editor with tools */
    protected $imageEditor;

    /**
     * ImageResource constructor.
     *
     * @param resource $source An image resource
     */
    public function __construct($source)
    {
        if (is_resource($source)) {
            $this->resource   = $source;
            $this->renderer   = $this->renderer ?: 'imagepng';
            $this->mimeString = $this->mimeString ?: 'image/png';
            $this->ext        = $this->ext ?: 'png';
        } else {
            throw new \InvalidArgumentException(
                'Argument 1 passed to ' . __METHOD__ . ' must be a resource, ' . gettype($source) . 'given.'
            );
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
     * @param string|resource $target File path or resource
     *
     * @return File|Boolean A new file reference if saved to a the file system. A boolean flag if the $target is a resource
     */
    public function renderTo($target)
    {
        // Run any pending edits
        $this->edit()->apply();
        $resource = $this->resource();

        if (is_string($target) && !pathinfo($target, PATHINFO_EXTENSION)) {
            $target .= $this->extension(true);
        }

        $saved = call_user_func($this->renderer, $resource, $target);

        if (is_string($target)) {
            return new File($target);
        }

        return $saved;
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
        $resource       = $this->resource();
        $this->resource = null;

        return $resource;
    }

    /**
     * Convert the image to different format
     *
     * @param int $imageType
     */
    public function convertTo($imageType)
    {
        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
            case IMAGETYPE_JPEG:
                $this->ext        = 'jpg';
                $this->mimeString = 'image/jpeg';
                $this->renderer   = 'imagejpeg';
                break;
            case 'png':
            case IMAGETYPE_PNG:
                $this->ext        = 'png';
                $this->mimeString = 'image/png';
                $this->renderer   = 'imagepng';
                break;
            case 'gif':
            case IMAGETYPE_GIF:
                $this->ext        = 'gif';
                $this->mimeString = 'image/gif';
                $this->renderer   = 'imagegif';
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
            $callback = function ($img) {
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
     *
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
     * Image file type extension
     *
     * @param bool $withDot Include dot
     *
     * @return string
     */
    public function extension($withDot = false)
    {
        return $withDot ? '.' . $this->ext : $this->ext;
    }

    /**
     * The mime type for the image
     *
     * @return string
     */
    public function mime()
    {
        return $this->mimeString ?: 'application/octet-stream';
    }
}
