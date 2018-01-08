<?php

namespace Darkroom\Recipe;

use Darkroom\Image;

/**
 * Class Resize resides an image
 *
 * @package Darkroom\Recipe
 */
class Resize extends AbstractRecipe
{
    const MODE_COLOR_FILL = 1;
    const MODE_IMAGE_FILL = 2;
    const MODE_FILL       = 3;
    const MODE_DISTORT    = 4;
    const MODE_RATIO      = 8;

    /** @var int The resize mode */
    protected $mode = self::MODE_RATIO;
    /** @var int New image height in pixels */
    protected $height;
    /** @var int New image width in pixels */
    protected $width;
    /** @var int The decimal percent to resize the image by */
    protected $decimalPercent = 1;

    /**
     * Set the dimensions of the new image
     *
     * @param int $width  New width in pixels
     * @param int $height New height in pixels
     *
     * @return Resize
     */
    public function to($width, $height = 0)
    {
        $this->width  = (int)$width;
        $this->height = (int)$height;

        $this->mode = ($this->width xor $this->height) ? self::MODE_RATIO : self::MODE_COLOR_FILL;

        return $this;
    }

    /**
     * Set the height of the new image
     *
     * @param int $height New height in pixels
     *
     * @return Resize
     */
    public function heightTo($height)
    {
        $this->mode = self::MODE_RATIO;

        $this->height = $height;
        return $this;
    }

    /**
     * Set the dimensions of the new image by a decimal percentage
     * Ex: 0.5 is 50%, 1.15 is 115%
     *
     * @param float $percent The percentage in decimal
     *
     * @return $this
     */
    public function by($percent)
    {
        if ($percent > 1) {
            $this->distort();
        } else {
            $this->mode = self::MODE_RATIO;
        }

        $this->decimalPercent = $percent;
        return $this;
    }

    /**
     * Keep original aspect ratio and use an image as the background
     *
     * @param Image $image The image to use as the background
     *
     * @return Resize
     */
    public function withImageFill(Image $image)
    {
        $this->mode      = self::MODE_IMAGE_FILL;
        $this->fillImage = $image;
        return $this;
    }

    /**
     * Keep original aspect ratio and use a color as the background
     *
     * @param string $color The background color
     *
     * @return Resize
     */
    public function withColorFill($color)
    {
        $this->mode  = self::MODE_COLOR_FILL;
        $this->color = $color;
        return $this;
    }

    /**
     * Ignore aspect ratio and distort the image if necessary
     *
     * @return Resize
     */
    public function distort()
    {
        if ($this->isMode(self::MODE_FILL)) {
            $this->mode = $this->mode | self::MODE_DISTORT;
        } else {
            $this->mode = self::MODE_DISTORT;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $image = $this->editor->image();

        if (!$this->height && !$this->width && $this->decimalPercent) {
            $this->height = $image->height() * $this->decimalPercent;
            $this->width  = $image->width() * $this->decimalPercent;
        }

        $source_x = $source_y = 0;
        $target_x = $target_y = 0;

        $source_width  = $image->width();
        $source_height = $image->height();

        $newWidth = $newHeight = 0;

        if ($this->isMode(self::MODE_RATIO | self::MODE_DISTORT)) {
            list($newWidth, $newHeight) = $this->newAspectSize();
        }

        if ($this->isMode(self::MODE_FILL)) {
            $newWidth  = $this->width ?: ($this->height * $source_width) / $source_height;
            $newHeight = $this->height ?: ($this->width * $source_height) / $source_width;

            list($sample_width, $sample_height) = $this->newAspectSize();

            // Center the sample in the new image
            $target_x = abs($sample_width - $newWidth) / 2;
            $target_y = abs($sample_height - $newHeight) / 2;
        }

        if (empty($sample_width)) {
            $sample_width = $newWidth;
        }

        if (empty($sample_height)) {
            $sample_height = $newHeight;
        }

        if ($newHeight && $newWidth) {
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled(
                $newImage,
                $image->resource(),
                $target_x,
                $target_y,
                $source_x,
                $source_y,
                $sample_width,
                $sample_height,
                $source_width,
                $source_height
            );

            return $newImage;
        }

        return null;
    }

    /**
     * Check if mode is enabled
     *
     * @param int $mode Mode to check if is enabled
     *
     * @return bool
     */
    protected function isMode($mode)
    {
        return ($this->mode & $mode) > 0;
    }

    /**
     * Calculate the dimensions of the new image sample while respecting the canvas size and original aspect ratio
     *
     * @return int[]
     */
    function newAspectSize()
    {
        $image = $this->editor->image();

        $max_width  = $this->width;
        $max_height = $this->height;

        $original_width  = $image->width();
        $original_height = $image->height();

        if ($this->isMode(self::MODE_DISTORT)) {
            $new_width  = $max_width;
            $new_height = $max_height;
        } else {
            $new_width  = $original_width > $max_width ? $max_width : $original_width;
            $new_height = $original_height > $max_height ? $max_height : $original_width;
        }

        if ($new_width xor $new_height) {
            $new_width  = $new_width ?: ($new_height * $original_width) / $original_height;
            $new_height = $new_height ?: ($new_width * $original_height) / $original_width;
        } else {
            if ($original_width > $new_width || $original_height > $new_height) {
                if ($original_width > $new_width) {
                    $new_height = ($original_height / $original_width) * $new_height;
                } else {
                    if ($original_height > $new_height) {
                        $new_width = ($original_width / $original_height) * $new_width;
                    }
                }
            }
        }

        return [round($new_width), round($new_height)];
    }
}
