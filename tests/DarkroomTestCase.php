<?php

namespace Darkroom\Tests;

class DarkroomTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Darkroom\Editor */
    protected $editor;

    protected function setUp()
    {
        $this->editor = \Darkroom\Editor::getInstance();
        parent::setUp();
    }

    protected function rectangleImage()
    {
        return $this->editor->open(__DIR__ . '/sample_files/rectangle.jpg');
    }

    /**
     * @return \Darkroom\Image
     */
    protected function squareImage()
    {
        return $this->editor->open(__DIR__ . '/sample_files/square.jpg');
    }
}
