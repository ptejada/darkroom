<?php

namespace Darkroom\Recipe;

use Darkroom\ImageEditor;

/**
 * Class AbstractRecipe
 *
 * @package Darkroom\Recipe
 */
abstract class AbstractRecipe
{
    /** @var ImageEditor The image editor */
    protected $editor;
    /** @var callable The callback to update the original image resource */
    protected $updater;
    /** @var bool Flag to keep track whether the recipe has been applied or not */
    protected $appliedFlag = false;

    /**
     * Crop constructor.
     *
     * @param $imageEditor $imageEditor
     */
    public function __construct(ImageEditor $imageEditor, callable $callback)
    {
        $this->editor  = $imageEditor;
        $this->updater = $callback;
    }

    /**
     * Apply the updates to the original image
     *
     * @return resource The updated image resource
     */
    abstract public function execute();

    /**
     * Saves the changes to the image
     *
     * @return ImageEditor The image editor
     */
    final public function apply()
    {
        $imgRef = $this->execute();

        $this->appliedFlag = true;
        call_user_func($this->updater, $imgRef);

        return $this->editor;
    }

    /**
     * Whether the filter has been applied or not
     *
     * @return bool
     */
    public function applied()
    {
        return $this->appliedFlag;
    }
}
