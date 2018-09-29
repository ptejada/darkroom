<?php

namespace Darkroom;

use Darkroom\Tool\Crop;
use Darkroom\Tool\Resize;
use Darkroom\Tool\Rotate;
use Darkroom\Tool\Stamp;
use Darkroom\Tool\Tool;

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
    /** @var Tool[] */
    protected $editQueue;

    /**
     * ImageEditor constructor.
     *
     * @param ImageResource $image
     * @param callable      $callback
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

    /**
     * Proxy calls to the tools factory
     *
     * @param string  $name      The tool accessor name
     * @param mixed[] $arguments Initial params
     *
     * @return Tool
     */
    public function __call($name, $arguments = [])
    {
        if ($tool = Editor::makeTool($name, $this, $this->updater)) {
            // Initialize tool
            call_user_func_array([$tool, 'init'], $arguments);

            // Register instance
            return $this->queue($tool);
        }

        throw new \BadMethodCallException(sprintf('Call to undefined function: %s::%s().', get_class($this), $name));
    }

    /**
     * Queues a new edit to be applied
     *
     * @param Tool $edit
     *
     * @return Tool
     */
    protected function queue(Tool $edit)
    {
        $this->editQueue[] = $edit;
        return $edit;
    }
}
