<?php

namespace Darkroom\Tests\Storage;

use Darkroom\Image;
use Darkroom\Storage\File;
use Darkroom\Storage\Store;
use Darkroom\Tests\DarkroomTestCase;

class StoreTest extends DarkroomTestCase
{
    public function testNewPath()
    {
        $store     = new Store();
        $imageFile = $this->squareImage()->file();
        $fileExt   = $imageFile->extension();

        /** @var Image| \PHPUnit_Framework_MockObject_MockObject $image */
        $image = $this->getMockBuilder(Image::class)
            ->setConstructorArgs([$imageFile])
            ->setMethods(['extension'])
            ->getMock();

        $image->expects($this->exactly(2))->method('extension')->with($this->isTrue())->willReturn('.' . $fileExt);

        $this->assertNotEmpty($store->newPath($image));
        $this->assertSame('myName.jpg', $store->newPath($image, 'myName'));
    }

    public function testSetPathPattern()
    {
        $store     = new Store();
        $imageFile = $this->squareImage();

        $this->assertNotEquals(5, strlen($store->newPath($imageFile)));
        $store->setPathPattern('%5');
        $this->assertEquals(5, strlen(pathinfo($store->newPath($imageFile), PATHINFO_FILENAME)));
    }

    public function testSave()
    {
        $store = new Store();
        $image = $this->squareImage();

        $store->setBasePath(sys_get_temp_dir());
        $store->setPathPattern('%5/%10');
        $file = $store->save($image);

        $this->assertInstanceOf(File::class, $file);
        $this->assertTrue($file->exists());
        $this->assertFileExists($file->filePath());
    }

    public function testSetBasePath()
    {
        $store     = new Store();
        $imageFile = $this->squareImage();

        $this->assertStringStartsNotWith(__DIR__, $store->newPath($imageFile));
        $store->setBasePath(__DIR__);
        $this->assertStringStartsWith(__DIR__, $store->newPath($imageFile));
    }

    public function testSetPathGenerator()
    {
        $store     = new Store();
        $imageFile = $this->squareImage();

        /** @var callable|\PHPUnit_Framework_MockObject_MockObject $generator */
        $generator = $this->getMockBuilder(\stdClass::class)->setMethods(['__invoke'])->getMock();
        $store->setPathGenerator($generator);
        $store->setBasePath(__DIR__);

        $random = 'generatedName';

        $generator->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($imageFile), $this->equalTo(__DIR__ . '/'), $this->equalTo('altName'))
            ->willReturn($random);

        $this->assertEquals($random, $store->newPath($imageFile, 'altName'));
    }

    public function testPathGeneratorWithCustomTypeReturn()
    {
        $store     = new Store();
        $imageFile = $this->squareImage();

        /** @var callable|\PHPUnit_Framework_MockObject_MockObject $generator */
        $generator = $this->getMockBuilder(\stdClass::class)->setMethods(['__invoke'])->getMock();
        $store->setPathGenerator($generator);

        $customObject = new \DateTime();

        $generator->expects($this->once())->method('__invoke')->willReturn($customObject);

        $this->assertSame($customObject, $store->newPath($imageFile));
    }

    public function testSaveFailure()
    {
        $store     = new Store();
        $imageFile = $this->squareImage();

        $store->setPathGenerator(function () use ($imageFile) {
            return $imageFile->file()->filePath();
        });

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to generate new image file name.');

        $store->save($imageFile);
    }

    public function testInvalidPathGenerator()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path generator must be callable, string given.');

        (new Store())->setPathGenerator('customFunction');
    }

    public function testInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exists or is not a directory.');

        (new Store())->setBasePath('/var/test/' . time());
    }

    /**
     * @return array
     */
    public function invalidPatternProvider()
    {
        return [
            [true],
            [false],
            [0123],
            [function () {
                return false;
            }],
        ];
    }

    /**
     * @param mixed $pattern
     *
     * @dataProvider invalidPatternProvider
     */
    public function testInvalidPathPattern($pattern)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The pattern must be a string');

        (new Store())->setPathPattern($pattern);
    }
}
