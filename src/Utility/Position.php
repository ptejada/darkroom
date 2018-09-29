<?php

namespace Darkroom\Utility;

class Position
{
    protected $overflow = false;
    /**
     * @var BoxInterface
     */
    private $container;
    /**
     * @var BoxInterface
     */
    private $element;
    /**
     * The anchor placement of the element
     *
     * @var int[]
     */
    private $element_anchor = [0, 0];

    public function __construct(BoxInterface $container, BoxInterface $element)
    {
        $this->container = $container;
        $this->element   = $element;
    }

    /**
     * Get the x and y offset of the element within the container
     */
    public function offsetFor($position)
    {
        list($positionX, $positionY) = $this->calcOffset($position);
        list($anchorX, $anchorY) = $this->element_anchor;

        $positionX -= $anchorX;
        $positionY -= $anchorY;

        if (!$this->overflow) {
            if ($positionX < 0) {
                $positionX = 0;
            }

            if ($positionX > $this->container->width()) {
                $positionX = $this->container->width() - $this->element->width();
            }

            if ($positionY < 0) {
                $positionY = 0;
            }

            if ($positionY > $this->container->width()) {
                $positionY = $this->container->height() - $this->element->height();
            }
        }

        return [$positionX, $positionY];
    }

    /**
     * Place the anchor point for the element
     *
     * @param $position
     */
    public function anchor($position)
    {
        $this->element_anchor = $this->calcOffset($position);
    }

    protected function calcOffset($position)
    {
        if (is_array($position) && count($position) > 1) {
            return [(int)$position[0], (int)$position[1]];
        }

        if (is_numeric($position)) {
            return [(int)$position, (int)$position];
        }

        if (is_string($position)) {
            if (strpos($position, ',')) {
                $delimiter = ',';
            } else {
                if (strpos($position, ' ')) {
                    $delimiter = ' ';
                } else {
                    if (strpos($position, '-')) {
                        $delimiter = '-';
                    }
                }
            }

            if (isset($delimiter)) {
                $cords     = explode($delimiter, $position);
                $positionX = isset($cords[0]) ? $cords[0] : 0;
                $positionY = isset($cords[1]) ? $cords[1] : $positionX;

                // TODO: Do text base positioning
                return [(int)$positionX, (int)$positionY];
            }
        }

        return [0, 0];
    }
}
