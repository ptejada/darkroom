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
        return $this->editor->open($this->sampleFile('rectangle.jpg'));
    }

    /**
     * @return \Darkroom\Image
     */
    protected function squareImage()
    {
        return $this->editor->open($this->sampleFile('square.jpg'));
    }

    /**
     * @return \Darkroom\Image
     */
    protected function stampImage()
    {
        return $this->editor->open($this->sampleFile('pt-tag.png'));
    }

    /**
     * Get the full path of a sample file
     *
     * @param string $name Sample file name
     *
     * @return string
     */
    protected function sampleFile($name)
    {
        return __DIR__ . '/sample_files/' . $name;
    }
}
