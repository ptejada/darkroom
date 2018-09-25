<?php

namespace Darkroom;

use Darkroom\Tool\Crop;
use Darkroom\Tool\Resize;
use Darkroom\Tool\Rotate;
use Darkroom\Tool\Stamp;
use Darkroom\Tool\Tool;
use Darkroom\Utility\Str;

/**
 * Class Editor
 *
 * @package Darkroom
 */
class SuperEditor
{
    /** @var Tool[] The list of tools */
    protected $tools;
    /** @var \Closure The file storage handler */
    protected $storageHandler;

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

        $this->storageHandler = function (Image $image){
            return Str::name('Y-m/%6-%4-%6');
        };

        // Automatically registers new editor instance
        Editor::useEditor($this);
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
     * @param Image $image
     *
     * @return File A reference of the saved file
     */
    public function save(Image $image)
    {
        $filePath = call_user_func($this->storageHandler, $image);
        if (is_string($filePath)) {
            $filePath .= '.' . $image->file()->extension();

            return $this->saveAs($image, $image->file()->directory() . $filePath);
        }

        return $filePath;
    }

    /**
     * @param Image $image
     * @param null  $path
     *
     * @return File|Boolean A new file reference if saved to a the file system. A boolean flag if the $target is a resource
     */
    public function saveAs(Image $image, $path = null)
    {
        return $image->renderTo($path);
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
