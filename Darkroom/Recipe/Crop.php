<?php

namespace Darkroom\Recipe;

use Darkroom\Editor;

/**
 * Class Crop crops an image
 *
 * @package Darkroom\Recipe
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
        $image  = $this->editor->image();
        $width  = $this->width ?: ($image->width() - $this->at_y);
        $height = $this->height ?: ($image->height() - $this->at_x);

        if (function_exists('imagecrop')) {
            // PHP 5.5+ Crop
            $img = imagecrop(
                $image->resource(),
                [
                    'x'      => $this->at_x,
                    'y'      => $this->at_y,
                    'width'  => $width,
                    'height' => $height,
                ]
            );
        } else {
            // PHP 5.4 Crop
            $img = Editor::canvas($width, $height)->resource();
            imagecopyresampled(
                $img,
                $image->resource(),
                0,
                0,
                $this->at_x,
                $this->at_y,
                $width,
                $height,
                $width,
                $height
            );
        }

        return $img;
    }
}
