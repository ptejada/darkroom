<?php

namespace Darkroom;

use Darkroom\Tool\Tool;

/**
 * Class Editor
 *
 * @method static Image open($imagePath) Opens an image.
 * @method static ImageResource canvas($width, $height) Creates a new blank canvas.
 * @method static File save(Image $image) Saves an image with an auto generated name.
 * @method static File saveAs(Image $image, $path) Saves an image to an specific path.
 * @method static void registerTool($accessorName, $toolClass) Registers a new editor tool.
 * @method static Tool makeTool($name, ImageEditor $toolClass, $updater) Create new tool instance by accessor name.
 *
 * @package Darkroom
 */
class Editor
{
    /** @var Tool[] */
    protected static $tools;
    /** @var SuperEditor */
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
        $editor = self::editor();
        if (method_exists($editor, $name)) {
            return call_user_func_array([$editor, $name], $params);
        }

        throw new \BadMethodCallException(sprintf('Call to undefined function: %s::%s().', get_class($editor), $name));
    }

    /**
     * Internal editor instance
     */
    protected static function editor()
    {
        if (empty(self::$editorInstance)) {
            self::$editorInstance = new SuperEditor();
        }

        return self::$editorInstance;
    }

    /**
     * Use alternative editor instance
     *
     * @param SuperEditor $editor An editor
     */
    public static function useEditor(SuperEditor $editor)
    {
        self::$editorInstance = $editor;
    }
}
