<?php

namespace Darkroom\Tests\Tool;

use Darkroom\Tests\DarkroomTestCase;

class RotateTest extends DarkroomTestCase
{
    public function testRight()
    {
        $image = $this->squareImage();
        $this->assertEquals(220, $image->width());

        $image->edit()->rotate()->right(45)->apply();

        $this->assertGreaterThan(300, $image->width());
    }

    public function testZeroConfig()
    {
        $image = $this->squareImage();
        $image->edit()->rotate();
        $this->assertTrue($image->renderTo(tmpfile()));
    }

    public function testWithTransparentFill()
    {
        $image = $this->squareImage();
        $this->assertEquals(220, $image->width());

        $image->edit()->rotate()->right(70)->withTransparentFill()->apply();

        $rgbIndex = imagecolorat($image->resource(), 0, 0);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(127, $channels['alpha']);
        $this->assertEquals(0, $channels['red']);
        $this->assertEquals(0, $channels['green']);
        $this->assertEquals(0, $channels['blue']);

        $this->assertGreaterThan(280, $image->width());
    }

    public function testLeft()
    {
        $image = $this->squareImage();
        $this->assertEquals(220, $image->width());

        $image->edit()->rotate()->left(45)->apply();

        $this->assertGreaterThan(300, $image->width());
    }

    public function testWithColorFill()
    {
        $image = $this->squareImage();
        $this->assertEquals(220, $image->width());

        $image->edit()->rotate()->right(70)->withColorFill([60, 70, 80, 20])->apply();

        $rgbIndex = imagecolorat($image->resource(), 0, 0);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(20, $channels['alpha']);
        $this->assertEquals(60, $channels['red']);
        $this->assertEquals(70, $channels['green']);
        $this->assertEquals(80, $channels['blue']);

        $this->assertGreaterThan(280, $image->width());
    }
}
