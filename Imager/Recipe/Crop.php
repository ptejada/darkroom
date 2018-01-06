<?php

namespace Imager\Recipe;

/**
 * Class Crop crops an image
 *
 * @package Imager\Recipe
 */
class Crop extends AbstractRecipe
{
    const TYPE_RECTANGLE = 1;
    // TODO: Implement the Oval crop
    const TYPE_OVAL = 2;

    /** @var int The crop type */
    protected $type = self::TYPE_RECTANGLE;
    /** @var int Viewport height in pixels */
    protected $height;
    /** @var int Viewport width in pixels */
    protected $width;
    /** @var int Viewport upper left corner x-axis position */
    protected $at_x;
    /** @var int Viewport upper left corner y-axis position */
    protected $at_y;

    /**
     * Set the dimensions of the rectangular viewport to crop
     *
     * @param int $width  The rectangle width in pixels
     * @param int $height The rectangle height in pixels
     *
     * @return Crop
     */
    public function rectangle($width, $height)
    {
        $this->type   = self::TYPE_RECTANGLE;
        $this->width  = abs((int)$width);
        $this->height = abs((int)$height);

        return $this;
    }

    /**
     * Set dimension of the square viewport to crop
     *
     * @param int $dimension The dimension of the square in pixels
     *
     * @return Crop
     */
    public function square($dimension)
    {
        return $this->rectangle($dimension, $dimension);
    }

    /**
     * Set the starting position of the upper left corner of the viewport
     *
     * @param int $x X axis position in pixels
     * @param int $y Y axis position in pixels
     *
     * @return Crop
     */
    public function at($x = 0, $y = 0)
    {
        $this->at_x = $x;
        $this->at_y = $y;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $image = $this->editor->image();

        $img = imagecrop(
            $image->resource(),
            [
                'x'      => $this->at_x,
                'y'      => $this->at_y,
                'width'  => $this->width ?: ($image->width() - $this->at_y),
                'height' => $this->height ?: ($image->height() - $this->at_x),
            ]
        );

        return $img;
    }
}
