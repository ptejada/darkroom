<?php

namespace Darkroom;

use Darkroom\Tool\AbstractTool;
use Darkroom\Tool\Crop;
use Darkroom\Tool\Resize;
use Darkroom\Tool\Rotate;
use Darkroom\Tool\Stamp;

/**
 * Class ImageEditor
 * @method Crop crop() Crop the image at the specified dimensions
 * @method Resize resize() Resize the image
 * @method Rotate rotate() Rotate the image
 * @method Stamp stamp() Stamp the image with another image
 *
 * @package Darkroom
 */
class ImageEditor
{
    /** @var Image The image to edit */
    protected $image;
    /** @var callable The callback to update the original image */
    protected $updater;
    /** @var AbstractTool[] */
    protected $editQueue;

    /**
     * ImageEditor constructor.
     *
     * @param ImageResource $image
     * @param callable $callback
     */
    public function __construct(ImageResource $image, callable $callback)
    {
        $this->image     = $image;
        $this->updater   = $callback;
        $this->editQueue = [];
    }

    /**
     * The image reference
     *
     * @return Image The image reference
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * Apply all pending edits to the image
     */
    public function apply()
    {
        foreach ($this->editQueue as $edit) {
            if (!$edit->applied()) {
                $edit->apply();
            }
        }

        // Clear the queue
        $this->editQueue = [];
    }

    public function __call($name, $arguments)
    {
        $className = '\Darkroom\Tool\\' . ucfirst($name);

        if (class_exists($className) && is_subclass_of($className, '\Darkroom\Tool\AbstractTool')) {
            // TODO: Check if implements interface
            return $this->queue(new $className($this, $this->updater));
        }

        // TODO: Throw exception
        return trigger_error(sprintf('Call to undefined function: %s::%s().', get_class($this), $name), E_USER_ERROR);
    }

    /**
     * Queues a new edit to be applied
     *
     * @param AbstractTool $edit
     *
     * @return AbstractTool
     */
    protected function queue(AbstractTool $edit)
    {
        $this->editQueue[] = $edit;
        return $edit;
    }
}
