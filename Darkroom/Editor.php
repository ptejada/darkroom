<?php

namespace Darkroom;

use Darkroom\Storage\ImageReference;
use Darkroom\Storage\PathScheme;
use League\Flysystem\Adapter\Local;

/**
 * Class Editor
 *
 * @package Darkroom
 */
class Editor
{
    /** @var \League\Flysystem\Filesystem The system to store the files */
    protected $storage;
    /** @var PathScheme The path scheme */
    protected $pathScheme;

    /**
     * Editor constructor.
     */
    private function __construct()
    {
        $this->pathScheme = new PathScheme();
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
     * @throws \League\Flysystem\FileExistsException
     */
    public static function saveSnapshot(Image $image)
    {
        $editor   = static::getInstance();
        $savePath = $editor->pathScheme->snapshot($image);
        $buffer   = tmpfile();

        $image->renderTo($buffer);
        $editor->storage()->writeStream($savePath, $buffer);

        if (is_resource($buffer)) {
            fclose($buffer);
        }

        return new ImageReference($savePath);
    }

    /**
     * Gets or configures the the storage filesystem
     *
     * @param \League\Flysystem\Filesystem|null $filesystem The optional filesystem to mount
     *
     * @return \League\Flysystem\Filesystem The mounted filesystem
     */
    public function storage(\League\Flysystem\Filesystem $filesystem = null)
    {
        if ($filesystem) {
            $this->storage = $filesystem;
        }

        if (empty($this->storage)) {
            $vendorFolder = strpos(__DIR__, '/vendor/');
            if ($vendorFolder) {
                $path = substr(__DIR__, 0, $vendorFolder) . '/';
            } else {
                $path = realpath(__DIR__ . '/../') . '/public/store/';
            }

            $adapter = new Local($path);

            $this->storage = new \League\Flysystem\Filesystem($adapter);
        }

        return $this->storage;
    }
}
