<?php

namespace Darkroom\Tests;

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
}
