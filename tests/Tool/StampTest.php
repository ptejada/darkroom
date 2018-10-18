<?php

namespace Darkroom\Tests\Tool;

use Darkroom\Tests\DarkroomTestCase;

class StampTest extends DarkroomTestCase
{
    public function testStamp()
    {
        $image = $this->rectangleImage();
        $stamp = $this->stampImage();

        $rgbIndex = imagecolorat($image->resource(), 10, 10);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(0, $channels['alpha']);
        $this->assertEquals(0, $channels['red']);
        $this->assertEquals(0, $channels['green']);
        $this->assertEquals(0, $channels['blue']);

        $image->edit()->stamp()->with($stamp)->at(0, 0)->apply();

        $rgbIndex = imagecolorat($image->resource(), 10, 10);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(0, $channels['alpha']);
        $this->assertNotEquals(0, $channels['red']);
        $this->assertNotEquals(0, $channels['green']);
        $this->assertNotEquals(0, $channels['blue']);
    }

    public function testWithOpacity()
    {
        $image = $this->rectangleImage();
        $stamp = $this->stampImage();

        $rgbIndex = imagecolorat($image->resource(), 10, 10);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(0, $channels['alpha']);
        $this->assertEquals(0, $channels['red']);
        $this->assertEquals(0, $channels['green']);
        $this->assertEquals(0, $channels['blue']);

        $image->edit()->stamp()->with($stamp)->opacity(.5)->at(-500, -500)->apply();

        $rgbIndex = imagecolorat($image->resource(), 10, 10);
        $channels = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(0, $channels['alpha']);
        $this->assertNotEquals(0, $channels['red']);
        $this->assertLessThan(60, $channels['red']);
        $this->assertNotEquals(0, $channels['green']);
        $this->assertLessThan(60, $channels['green']);
        $this->assertNotEquals(0, $channels['blue']);
        $this->assertLessThan(60, $channels['blue']);
    }

    public function testZeroConfig()
    {
        $image = $this->rectangleImage();
        $stamp = $this->stampImage();

        $originalResource = $image->resource();

        $image->edit()->stamp()->with($stamp)->apply();

        $this->assertSame($originalResource, $image->resource());
    }

    public function testOverflowAndMultiStamp()
    {
        $image = $this->squareImage();
        $stamp = $this->stampImage();

        $originalResource = $image->resource();

        $image->edit()->stamp()->with($stamp)->at(0, 0)->at(500, 500)->apply();

        $this->assertSame($originalResource, $image->resource());

        $rgbIndex = imagecolorat($image->resource(), 10, 10);
        $channels1 = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals(0, $channels1['alpha']);
        $this->assertNotEquals(0, $channels1['red']);
        $this->assertNotEquals(0, $channels1['green']);
        $this->assertNotEquals(0, $channels1['blue']);

        $rgbIndex = imagecolorat($image->resource(), 210, 210);
        $channels2 = imagecolorsforindex($image->resource(), $rgbIndex);

        $this->assertEquals($channels1['alpha'], $channels2['alpha']);
        $this->assertEquals($channels1['red'], $channels2['red']);
        $this->assertEquals($channels1['green'], $channels2['green']);
        $this->assertEquals($channels1['blue'], $channels2['blue']);
    }
}
