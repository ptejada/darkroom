<?php

namespace Darkroom;

use Darkroom\Storage\File;
use Darkroom\Tool\Tool;

/**
 * Class Editor
 * @method static Image open($imagePath) Opens an image.
 * @method static ImageResource canvas($width, $height = 0) Creates a new blank canvas.
 * @method static File save(Image $image, $altName = null) Saves an image in the storage.
 * @method static void registerTool($accessorName, $toolClass) Registers a new editor tool.
 * @method static Tool makeTool($name, ImageEditor $toolClass, $updater) Create new tool instance by accessor name.
 *
 * @package Darkroom
 */
class Editor
{
    /** @var Tool[] */
    protected static $tools;
    /** @var EditorConfig */
    protected static $editorInstance;

    /**
     * Proxies all static methods to instance
     *
     * @param string  $name   The method name
     * @param mixed[] $params List of call parameters
     *
     * @return mixed
     */
    public static function __callStatic($name, $params = [])
    {
        $editor = self::config();
        if (method_exists($editor, $name)) {
            return call_user_func_array([$editor, $name], $params);
        }

        throw new \BadMethodCallException(sprintf('Call to undefined function: %s::%s().', get_class($editor), $name));
    }

    /**
     * Internal editor configuration
     */
    public static function config()
    {
        if (empty(self::$editorInstance)) {
            self::$editorInstance = new EditorConfig();
        }

        return self::$editorInstance;
    }

    /**
     * Use alternative editor instance
     *
     * @param EditorConfig $editor An editor
     */
    public static function useEditor(EditorConfig $editor)
    {
        self::$editorInstance = $editor;
    }
}
