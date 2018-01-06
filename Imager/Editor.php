<?php

namespace Imager;

use Imager\Storage\Filesystem;

/**
 * Class Editor
 *
 * @package Imager
 */
class Editor
{
    /** @var Filesystem The system to store the files */
    protected $storage;

    /**
     * Editor constructor.
     */
    public function __construct()
    {
        $this->storage = new Filesystem();
    }

    /**
     * Opens an image
     *
     * @param string $imagePath Path to the image
     *
     * @return Image
     */
    public function open($imagePath)
    {
        $file = new File($imagePath);
        if ($file->exists()) {
            return new Image($this, $file);
        }

        // TODO: Handle non existing files
    }

    /**
     * @param Image $image
     *
     * @return Storage\ImageReference
     */
    public function saveSnapshot(Image $image)
    {
        return $this->storage()->saveImageSnapshot($image);
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
