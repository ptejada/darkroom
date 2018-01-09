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
