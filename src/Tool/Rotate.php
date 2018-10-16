<?php

namespace Darkroom\Tool;

use Darkroom\Utility\Color;

/**
 * Class Rotate rotates an image
 *
 * @package Darkroom\Tool
 */
class Rotate extends AbstractTool
{
    const FILL_COLOR       = 1;
    const FILL_TRANSPARENT = 2;

    /** @var int The angle to rotate the image by */
    protected $angle = 0;
    /** @var Color The background color */
    protected $color;
    /** @var bool Flag to whether the fill be transparent */
    protected $transparent;
    protected $mode;

    /**
     * Rotate the image to left
     *
     * @param float $degrees Degrees to rotate the image
     *
     * @return $this
     */
    public function left($degrees)
    {
        $this->angle = -(float) $degrees;
        return $this;
    }

    /**
     * Rotate the image to the right
     *
     * @param float $degrees Degrees to rotate the image
     *
     * @return $this
     */
    public function right($degrees)
    {
        $this->angle = abs((float) $degrees);
        return $this;
    }

    /**
     * Specify the color for the uncovered zone of the image after the rotation
     *
     * @param string|Color $color Background color
     *
     * @return $this
     */
    public function withColorFill($color = 'black')
    {
        $this->mode  = self::FILL_COLOR;
        $this->color = $color instanceof Color ? $color : new Color($color);
        return $this;
    }

    /**
     * Make the uncovered area transparent
     *
     * @return $this
     */
    public function withTransparentFill()
    {
        $this->mode        = self::FILL_TRANSPARENT;
        $this->transparent = true;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function execute()
    {
        if ($this->angle) {
            $image = $this->editor->image()->resource();

            if (!$this->color) {
                $this->color = new Color('black');
            }

            if ($this->transparent) {
                // TODO: Make the transparency work with the GIF images
                $color = imagecolorallocatealpha($image, 0, 0, 0, 127);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                // Convert image to PNG
                $this->editor->image()->convertTo(IMAGETYPE_PNG);
            } else {
                $color = $this->color->color($image);
            }

            // TODO: Look into the usage for the last argument
            return imagerotate($image, $this->angle, $color, 0);
        }

        return null;
    }
}
