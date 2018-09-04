<?php

namespace Darkroom\Utility;

/**
 * Interface BoxInterface
 *
 * @package Darkroom\Utility
 */
interface BoxInterface
{
    /**
     * The width of the box in pixels
     *
     * @return int
     */
    public function width();

    /**
     * The height of the box in pixels
     *
     * @return int
     */
    public function height();
}
