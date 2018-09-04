<?php

namespace Darkroom;

/**
 * Class Editor
 *
 * @package Darkroom
 */
class Editor
{
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
     * @return File A reference of the saved file
     */
    public static function save(Image $image)
    {
        return self::saveAs($image, $image->file()->directory() . $image->file()->name());
    }

    /**
     * @param Image $image
     * @param null  $path
     *
     * @return File|Boolean A new file reference if saved to a the file system. A boolean flag if the $target is a resource
     */
    public static function saveAs(Image $image, $path = null)
    {
        return $image->renderTo($path);
    }
}
