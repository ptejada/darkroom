<?php

namespace Darkroom;

use Darkroom\Storage\File;
use Darkroom\Storage\Storage;
use Darkroom\Storage\Store;
use Darkroom\Tool\Crop;
use Darkroom\Tool\Resize;
use Darkroom\Tool\Rotate;
use Darkroom\Tool\Stamp;
use Darkroom\Tool\Tool;

/**
 * Class EditorConfig
 *
 * @package Darkroom
 */
class EditorConfig
{
    /** @var Tool[] The list of tools */
    protected $tools;
    /** @var Storage Image storage */
    protected $storage;

    /**
     * SuperEditor constructor initializes the list of built-in tools
     */
    public function __construct()
    {
        $this->tools = [
            'crop'   => Crop::class,
            'resize' => Resize::class,
            'rotate' => Rotate::class,
            'stamp'  => Stamp::class,
        ];

        // Automatically registers new editor instance
        Editor::useEditor($this);

        $this->storage = new Store();
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
    public function canvas($width, $height = 0)
    {
        $height = $height ?: $width;
        return new ImageResource(imagecreatetruecolor($width, $height));
    }

    /**
     * Saves an image in the storage
     *
     * @param Image  $image   The image reference
     * @param string $altName Alternative file name
     *
     * @return File A reference of the saved file
     */
    public function save(Image $image, $altName = null)
    {
        return $this->storage->save($image, $altName);
    }

    /**
     * The storage
     *
     * @return Storage|Store
     */
    public function storage()
    {
        return $this->storage;
    }

    /**
     * Updates the internal storage
     *
     * @param Storage $store Storage to use
     */
    public function useStorage(Storage $store)
    {
        $this->storage = $store;
    }

    /**
     * Register a new tool
     *
     * @param string $accessorName The name with which the tool will be accessed from the image editor
     * @param string $tool         The tool class
     */
    public function registerTool($accessorName, $tool)
    {
        $this->tools[$accessorName] = $tool;
    }

    /**
     * Makes tool instance
     *
     * @param  string     $name    The tool accessor name
     * @param ImageEditor $editor  The image editor
     * @param             $updater Callback executed when resource is updated
     *
     * @return Tool
     */
    public function makeTool($name, ImageEditor $editor, $updater)
    {
        if (isset($this->tools[$name])) {
            return new $this->tools[$name]($editor, $updater);
        }

        return null;
    }
}
