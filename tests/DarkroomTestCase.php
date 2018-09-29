<?php

namespace Darkroom\Tests;

use Darkroom\EditorConfig;

class DarkroomTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Darkroom\EditorConfig */
    protected $editor;

    protected function setUp()
    {
        $this->editor = new EditorConfig();
        parent::setUp();
    }

    /**
     * @return \Darkroom\Image
     */
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

    /**
     * @return \Darkroom\Image
     */
    protected function stampImage()
    {
        return $this->editor->open(__DIR__ . '/sample_files/pt-tag.png');
    }
}
