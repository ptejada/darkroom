<?php

namespace Darkroom\Tests\Storage;

use Darkroom\Storage\File;
use Darkroom\Tests\DarkroomTestCase;

class FileTest extends DarkroomTestCase
{
    public function testValidFile()
    {
        $filePath = $this->sampleFile('square.gif');
        $file     = new File($filePath);

        $this->assertTrue($file->exists());
        $this->assertEquals('square', $file->name());
        $this->assertEquals('square.gif', $file->filename());
        $this->assertEquals($filePath, $file->filePath());
        $this->assertEquals(dirname($filePath), $file->directory());
        $this->assertFalse($file->isURL());
    }

    public function testInvalidFile()
    {
        $filePath = 'http://ptejada.com/fake.gif';
        $file     = new File($filePath);

        $this->assertFalse($file->exists());
        $this->assertEquals('fake', $file->name());
        $this->assertEquals('fake.gif', $file->filename());
        $this->assertEquals($filePath, $file->filePath());
        $this->assertEquals(dirname($filePath), $file->directory());
        $this->assertTrue($file->isURL());
    }

    public function testFromUrl()
    {
        $file = new File('https://ptejada.com/favicon.png');
        $this->assertTrue($file->exists());
    }
}
