<?php

namespace Darkroom\Tests;

use Darkroom\ImageResource;
use Darkroom\Storage\File;

class ImageResourceTest extends DarkroomTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument 1 passed to Darkroom\ImageResource::__construct must be a resource, string given.
     */
    public function testInvalidSource()
    {
        new ImageResource('square.gif');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRendering()
    {
        $img = new ImageResource($this->squareImage()->detach());

        $this->expectOutputRegex('/PNG/');
        $img->render();
    }

    public function testRenderToFile()
    {
        $img = new ImageResource($this->squareImage()->detach());
        $tmpFile = tempnam(sys_get_temp_dir(), 'img');

        $file = $img->renderTo($tmpFile);

        $this->assertInstanceOf(File::class, $file);
        $this->assertStringEndsWith('.png', $file->filePath());
        $this->assertNotEquals($tmpFile, $file->filePath());
        $this->assertStringStartsWith($tmpFile, $file->filePath());
    }

    public function testCovert()
    {
        $img = new ImageResource($this->squareImage()->detach());

        $this->assertEquals('png', $img->extension());
        $img->convertTo('jpg');
        $this->assertEquals('jpg', $img->extension());
        $img->convertTo('gif');
        $this->assertEquals('gif', $img->extension());
        $img->convertTo(IMAGETYPE_PNG);
        $this->assertEquals('png', $img->extension());
        $img->convertTo(IMAGETYPE_JPEG);
        $this->assertEquals('jpg', $img->extension());
        $img->convertTo(IMAGETYPE_GIF);
        $this->assertEquals('gif', $img->extension());
    }
}
