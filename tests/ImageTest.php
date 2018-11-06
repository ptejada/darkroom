<?php

namespace Darkroom\Tests;

use Darkroom\Image;
use Darkroom\Storage\File;

class ImageTest extends DarkroomTestCase
{
    public function imageProvider()
    {
        return [
            ['square.jpg', 'image/jpeg', 'jpg'],
            ['square.gif', 'image/gif', 'gif'],
            ['square.png', 'image/png', 'png'],
        ];
    }

    /**
     * @param string $fileName The file name
     * @param string $mime The mime string
     * @param string $ext The extension
     *
     * @dataProvider imageProvider
     */
    public function testLoadingImageTypes($fileName, $mime, $ext)
    {
        $image = $this->editor->open($this->sampleFile($fileName));

        $this->assertEquals($mime, $image->mime());
        $this->assertEquals($ext, $image->extension());
    }

    public function testInvalidImageSource()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot open file: test');

        new Image(new File('test'));
    }

    public function testUnsupportedFileType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File type text/x-php is not supported');

        new Image(new File(__FILE__));
    }

    public function testSavingImage()
    {
        $this->assertInstanceOf(File::class, $this->squareImage()->save());
    }

    public function testInvalidImageEditor()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Call to undefined function: Darkroom\ImageEditor::whiteBackground()');

        $this->squareImage()->edit()->whiteBackground();
    }
}
