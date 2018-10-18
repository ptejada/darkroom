<?php

namespace Darkroom\Tool;

use Darkroom\ImageResource;

/**
 * Class Stamp inserts an image into another
 *
 * @package Darkroom\Tool
 */
class Stamp extends AbstractTool
{
    /** @var ImageResource */
    protected $stamp;
    /** @var int[][] */
    protected $placements = [];
    /** @var float The opacity level between 1 and 0 */
    protected $opacity;

    /**
     * Set the image to use as a stamp
     *
     * @param ImageResource $image The stamp image
     *
     * @return Stamp
     */
    public function with(ImageResource $image)
    {
        $this->stamp = $image;

        return $this;
    }

    /**
     * Specify the where to position the stamp
     * TODO: Add support for the position utility
     *
     * @param int $x
     * @param int $y
     *
     * @return Stamp
     */
    public function at($x, $y)
    {
        $this->placements[] = [$x, $y];
        return $this;
    }

    /**
     * Set the opacity for the stamp image
     *
     * @param float $opacity The decimal opacity level for the stamp where 1 is opaque and 0 is transparent
     *
     * @return Stamp
     */
    public function opacity($opacity)
    {
        $opacity       = $opacity > 1 ? 1 : $opacity;
        $this->opacity = 127 - round(127 * $opacity);

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function execute()
    {
        if ($this->stamp && !empty($this->placements)) {
            $baseImage = $this->editor->image();
            $stamp     = $this->stamp;

            if ($this->opacity) {
                // imagesavealpha can only be used by doing this for some reason
                imagealphablending($stamp->resource(), false);
                imagesavealpha($stamp->resource(), true);
                imagefilter($stamp->resource(), IMG_FILTER_COLORIZE, 0, 0, 0, $this->opacity);
            }

            foreach ($this->placements as $cordidates) {
                list($at_x, $at_y) = $cordidates;

                $at_x = $at_x < 0 ? 0 : $at_x;
                $at_y = $at_y < 0 ? 0 : $at_y;

                if ($at_x + $stamp->width() > $baseImage->width()) {
                    $at_x = $baseImage->width() - $stamp->width();
                }

                if ($at_y + $stamp->height() > $baseImage->height()) {
                    $at_y = $baseImage->height() - $stamp->height();
                }

                imagecopy($baseImage->resource(), $stamp->resource(), $at_x, $at_y, 0, 0, $stamp->width(),
                    $stamp->height()
                );
            }

            return $baseImage->resource();
        }

        return null;
    }
}
