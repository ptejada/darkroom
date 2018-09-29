<?php

namespace Darkroom\Tests\Tool;

use Darkroom\Tests\DarkroomTestCase;

class CropTest extends DarkroomTestCase
{
    public function testSquareCrop()
    {
        $image = $this->squareImage();

        $this->assertEquals(220, $image->width());
        $this->assertEquals(220, $image->height());

        $image->edit()->crop()->square(100)->apply();

        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
    }

    public function testUpCrop()
    {
        $image = $this->squareImage();

        $this->assertEquals(220, $image->width());
        $this->assertEquals(220, $image->height());

        $image->edit()->crop()->square(500)->apply();

        $this->assertEquals(500, $image->width());
        $this->assertEquals(500, $image->height());
    }

    public function testCropRectangle()
    {
        $image = $this->squareImage();

        $this->assertEquals(220, $image->width());
        $this->assertEquals(220, $image->height());

        $image->edit()->crop()->rectangle(220, 100)->at(0, 60)->apply();

        $this->assertEquals(220, $image->width());
        $this->assertEquals(100, $image->height());
    }

    public function testCropOverflow()
    {
        $image = $this->rectangleImage();

        $this->assertEquals(400, $image->width());
        $this->assertEquals(270, $image->height());

        $image->edit()->crop()->square(50)->at(500, 500)->apply();

        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }
}
