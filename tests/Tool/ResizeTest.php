<?php

namespace Darkroom\Tests\Tool;

use Darkroom\Tests\DarkroomTestCase;

class ResizeTest extends DarkroomTestCase
{
    public function testSquareToRectangle()
    {
        $new_width  = 300;
        $new_height = 200;
        $image      = $this->squareImage();

        $this->assertNotEquals($new_width, $image->width());
        $this->assertNotEquals($new_height, $image->height());

        $image->edit()->resize()->to($new_width, $new_height)->apply();

        $this->assertEquals($new_width, $image->width());
        $this->assertEquals($new_height, $image->height());
    }

    public function testRectangleToSquare()
    {
        $dimension = 200;
        $image     = $this->rectangleImage();

        $this->assertNotEquals($dimension, $image->width());
        $this->assertNotEquals($dimension, $image->height());
        $this->assertNotEquals($image->width(), $image->height());

        $image->edit()->resize()->to($dimension, $dimension)->apply();

        $this->assertEquals($dimension, $image->width());
        $this->assertEquals($dimension, $image->height());
    }

    public function testSquareAutoHeight()
    {
        $image          = $this->rectangleImage();
        $new_width      = 200;
        $new_height     = 135;
        $original_ratio = round($image->width() / $image->height(), 2);

        $this->assertNotEquals($new_width, $image->width());
        $this->assertNotEquals($new_height, $image->height());

        $image->edit()->resize()->to($new_width)->apply();

        $this->assertEquals($new_width, $image->width());
        $this->assertEquals($new_height, $image->height());

        $new_ratio = round($new_width / $new_height, 2);
        $this->assertGreaterThanOrEqual($original_ratio - 0.01, $new_ratio);
        $this->assertLessThanOrEqual($original_ratio + 0.01, $new_ratio);
    }

    public function testSquareAutoWidth()
    {
        $image          = $this->rectangleImage();
        $new_width      = 200;
        $new_height     = 135;
        $original_ratio = round($image->width() / $image->height(), 2);

        $this->assertNotEquals($new_width, $image->width());
        $this->assertNotEquals($new_height, $image->height());

        $image->edit()->resize()->heightTo($new_height)->apply();

        $this->assertEquals($new_width, $image->width());
        $this->assertEquals($new_height, $image->height());

        $new_ratio = round($new_width / $new_height, 2);
        $this->assertGreaterThanOrEqual($original_ratio - 0.01, $new_ratio);
        $this->assertLessThanOrEqual($original_ratio + 0.01, $new_ratio);
    }

    /**
     * @param int $new_width
     * @param int $new_height
     *
     * @dataProvider dimensionProvider
     */
    public function testAnyDistort($new_width, $new_height)
    {
        $square_image = $this->squareImage();
        $this->assertNotEquals($new_width, $square_image->width());
        $this->assertNotEquals($new_height, $square_image->height());

        $square_image->edit()->resize()->to($new_width, $new_height)->distort()->apply();

        $this->assertEquals($new_width, $square_image->width());
        $this->assertEquals($new_height, $square_image->height());

        $rectangle_image = $this->rectangleImage();
        $this->assertNotEquals($new_width, $rectangle_image->width());
        $this->assertNotEquals($new_height, $rectangle_image->height());

        $rectangle_image->edit()->resize()->to($new_width, $new_height)->distort()->apply();

        $this->assertEquals($new_width, $rectangle_image->width());
        $this->assertEquals($new_height, $rectangle_image->height());
    }

    public function dimensionProvider()
    {
        return [
            [150, 150],
            [50, 100],
            [100, 50],
        ];
    }

    public function testSmallerSizeAutoWidth()
    {
        $image      = $this->rectangleImage();
        $new_width  = 148;
        $new_height = 100;

        $image->edit()->resize()->heightTo($new_height)->apply();

        $this->assertEquals($new_height, $image->height());
        $this->assertEquals($new_width, $image->width());
    }

    public function testSmallerSizeAutoHeight()
    {
        $image      = $this->rectangleImage();
        $new_width  = 148;
        $new_height = 100;

        $image->edit()->resize()->to($new_width)->apply();

        $this->assertEquals($new_height, $image->height());
        $this->assertEquals($new_width, $image->width());
    }

    public function testResizeByPercent()
    {
        $image = $this->rectangleImage();

        $image->edit()->resize()->by(0.5)->apply();

        $this->assertEquals(400 / 2, $image->width());
        $this->assertEquals(270 / 2, $image->height());
    }

    public function testUpscaleResize()
    {
        $image = $this->rectangleImage();

        $image->edit()->resize()->by(1.5)->apply();

        $this->assertEquals(400 * 1.5, $image->width());
        $this->assertEquals(270 * 1.5, $image->height());
    }

    public function colorProvider()
    {
        return [
            ['green'],
            ['#008000'],
            [[0, 128, 0]],
        ];
    }

    /**
     * @dataProvider  colorProvider
     * @param mixed $color
     */
    public function testUpscaleWithColorFill($color)
    {
        $image = $this->squareImage();

        $image->edit()->resize()->to(500)->withColorFill($color)->apply();

        $rgbIndex = imagecolorat($image->resource(), 0 ,0);

        $this->assertEquals(32768, $rgbIndex);

        $this->assertEquals(500, $image->width());
        $this->assertEquals(500, $image->height());
    }

    public function testUpscaleWithTransparentFill()
    {
        $image = $this->squareImage();

        $image->edit()->resize()->to(500)->withTransparentFill()->apply();

        $rgbIndex = imagecolorat($image->resource(), 0 ,0);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(127, $channels['alpha']);
        $this->assertEquals(0, $channels['red']);
        $this->assertEquals(0, $channels['green']);
        $this->assertEquals(0, $channels['blue']);

        $this->assertEquals(500, $image->width());
        $this->assertEquals(500, $image->height());
    }

    public function testUpscaleWithImageFill()
    {
        $image = $this->squareImage();

        $image->edit()->resize()->to(500)->withImageFill($this->stampImage())->apply();

        $this->assertEquals(4013373, imagecolorat($image->resource(), 50, 50));
        $this->assertEquals(16777215, imagecolorat($image->resource(), 50, 250));

        $this->assertEquals(500, $image->width());
        $this->assertEquals(500, $image->height());
    }

    public function testInvalidExec()
    {
        $image = $this->squareImage();
        $initial = $image->resource();
        $image->edit()->resize();
        $image->edit()->apply();

        $this->assertSame($initial, $image->resource());
    }
}
