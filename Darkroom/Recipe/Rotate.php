<?php

namespace Darkroom\Recipe;

use Darkroom\Utility\Color;

/**
 * Class Rotate rotates an image
 *
 * @package Darkroom\Recipe
 */
class Rotate extends AbstractRecipe
{
    /** @var int The angle to rotate the image by */
    protected $angle = 0;
    /** @var Color The background color */
    protected $color;

    /**
     * Rotate the image to left
     *
     * @param float $degrees Degrees to rotate the image
     * @return $this
     */
    public function left($degrees)
    {
        $this->angle = -(float)$degrees;
        return $this;
    }

    /**
     * Rotate the image to the right
     *
     * @param float $degrees Degrees to rotate the image
     * @return $this
     */
    public function right($degrees)
    {
        $this->angle = abs((float)$degrees);
        return $this;
    }

    /**
     * Specify the color for the uncovered zone of the image after the rotation
     *
     * @param string|Color $color Background color
     * @return $this
     */
    public function withColorFill($color)
    {
        $this->color = $color instanceof Color ? $color : new Color($color);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        // TODO: Add color transparency as option
        if ($this->angle) {
            $image = $this->editor->image()->resource();
            return imagerotate($image, $this->angle, $this->color->color($image));
        }

        return null;
    }
}
