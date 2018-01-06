<?php

namespace Imager;

use Imager\Recipe\AbstractRecipe;
use Imager\Recipe\Crop;
use Imager\Recipe\Resize;

/**
 * Class ImageEditor
 * @method Crop crop() Crop the image at the specified dimensions
 * @method Resize resize() Resize the image
 *
 * @package Imager
 */
class ImageEditor
{
    /** @var Image The image to edit */
    protected $image;
    /** @var callable The callback to update the original image */
    protected $updater;
    /** @var AbstractRecipe[] */
    protected $recipeQueue;

    /**
     * ImageEditor constructor.
     *
     * @param Image    $image
     * @param callable $callback
     */
    public function __construct(Image $image, callable $callback)
    {
        $this->image       = $image;
        $this->updater     = $callback;
        $this->recipeQueue = [];
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
        foreach ($this->recipeQueue as $recipe) {
            if (!$recipe->applied()) {
                $recipe->apply();
            }
        }

        // Clear the queue
        $this->recipeQueue = [];
    }

    public function __call($name, $arguments)
    {
        $className = '\Imager\Recipe\\' . ucfirst($name);

        if (class_exists($className) && is_subclass_of($className, AbstractRecipe::class)) {
            // TODO: Check if implements interface
            return $this->queue(new $className($this, $this->updater));
        }

        // TODO: Throw exception
        return trigger_error(sprintf('Call to undefined function: %s::%s().', get_class($this), $name), E_USER_ERROR);
    }

    /**
     * Queues a new recipe to be applied
     *
     * @param AbstractRecipe $recipe
     *
     * @return AbstractRecipe
     */
    protected function queue($recipe)
    {
        $this->recipeQueue[] = $recipe;
        return $recipe;
    }
}
