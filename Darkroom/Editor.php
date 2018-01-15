<?php

namespace Darkroom;

use Darkroom\Storage\Filesystem;

/**
 * Class Editor
 *
 * @package Darkroom
 */
class Editor
{
    /** @var Filesystem The system to store the files */
    protected $storage;

    /**
     * Editor constructor.
     */
    private function __construct()
    {
        $this->storage = new Filesystem();
    }

    /**
     * The editor instance
     *
     * @return static
     */
    public static function getInstance()
    {
        static $instance;

        if (empty($instance)) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Opens an image
     *
     * @param string $imagePath Path to the image
     *
     * @return Image
     */
    public static function open($imagePath)
    {
        $file = new File($imagePath);
        if ($file->exists()) {
            return new Image($file);
        }

        // TODO: Handle non existing files
    }

    /**
     * Create new blank image canvas
     *
     * @param int $width  Width in pixels. If only the width is provided a square canvas will be created.
     * @param int $height Height in pixels
     *
     * @return ImageResource
     */
    public static function canvas($width, $height = 0)
    {
        $height = $height ?: $width;
        return new ImageResource(imagecreatetruecolor($width, $height));
    }

    /**
     * @param Image $image
     *
     * @return Storage\ImageReference
     */
    public static function saveSnapshot(Image $image)
    {
        return static::getInstance()->storage()->saveImageSnapshot($image);
    }

    /**
     * The image storage
     *
     * @return Filesystem
     */
    protected function storage()
    {
        return $this->storage;
    }
}
