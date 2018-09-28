<?php

namespace Darkroom\Tool;

use Darkroom\ImageEditor;
use Darkroom\ImageResource;

/**
 * Class AbstractTool
 *
 * @package Darkroom\Tool
 */
abstract class AbstractTool implements Tool
{
    /** @var ImageEditor The image editor */
    protected $editor;
    /** @var callable The callback to update the original image resource */
    protected $updater;
    /** @var bool Flag to keep track whether the tool has been applied or not */
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
     * @inheritdoc
     */
    public function init()
    {
        //
    }

    /**
     * Apply the updates to the original image
     *
     * @return ImageResource The updated image resource
     */
    abstract protected function execute();

    /**
     * Saves the changes to the image
     *
     * @return ImageEditor The image editor
     */
    final public function apply()
    {
        $imgRef = $this->execute();

        $this->appliedFlag = true;
        if ($imgRef instanceof ImageResource) {
            call_user_func($this->updater, $imgRef->detach());
        } else {
            call_user_func($this->updater, $imgRef);
        }

        return $this->editor;
    }

    /**
     * Whether the filter has been applied or not
     *
     * @return bool
     */
    final public function applied()
    {
        return $this->appliedFlag;
    }
}
