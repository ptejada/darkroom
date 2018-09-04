<?php

namespace Darkroom\Tests;

use Darkroom\Editor;

class EditorTest extends \DarkroomTestCase
{
    public function test_square_canvas()
    {
        $image = Editor::canvas(100);

        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
    }

    public function test_rectangle_canvas()
    {
        $image = Editor::canvas(100, 200);

        $this->assertEquals(100, $image->width());
        $this->assertEquals(200, $image->height());
    }
}
