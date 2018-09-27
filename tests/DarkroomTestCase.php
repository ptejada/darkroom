<?php

namespace Darkroom\Tests;

use Darkroom\EditorConfig;

class DarkroomTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Darkroom\Editor */
    protected $editor;

    protected function setUp()
    {
        $this->editor = new EditorConfig();
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
