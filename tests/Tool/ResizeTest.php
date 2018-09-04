<?php

namespace Darkroom\Tests\Tool;

class ResizeTest extends \DarkroomTestCase
{
    public function test_square_to_rectangle()
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

    public function test_rectangle_to_square()
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

    public function test_square_auto_height()
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

    public function test_square_auto_width()
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
    public function test_any_distort($new_width, $new_height)
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

    public function test_smaller_size_auto_width()
    {
        $image      = $this->rectangleImage();
        $new_width  = 148;
        $new_height = 100;

        $image->edit()->resize()->heightTo($new_height)->apply();

        $this->assertEquals($new_height, $image->height());
        $this->assertEquals($new_width, $image->width());
    }

    public function test_smaller_size_auto_height()
    {
        $image      = $this->rectangleImage();
        $new_width  = 148;
        $new_height = 100;

        $image->edit()->resize()->to($new_width)->apply();

        $this->assertEquals($new_height, $image->height());
        $this->assertEquals($new_width, $image->width());
    }

}
