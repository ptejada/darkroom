<?php

namespace Darkroom\Utility;

class PositionTest extends \DarkroomTestCase
{

    /**
     * @param mixed $pos
     * @param int[] $outcome
     *
     * @dataProvider positionProvider()
     */
    public function testOffsetFor($pos, $outcome)
    {
        $container = $this->editor->canvas(100);
        $element   = $this->editor->canvas(25);

        $position = new Position($container, $element);
        $this->assertEquals($outcome, $position->offsetFor($pos));
    }

    public function positionProvider()
    {
        return [
            ['0', [0, 0]],
            ['0 0', [0, 0]],
            ['0,0', [0, 0]],
            ['0-0', [0, 0]],
            ['5,10', [5, 10]],
            ['10,25', [10, 25]],
            ['125,180', [75, 75]],
            ['125,0', [75, 0]],
            ['0,180', [0, 75]],
            [[25,45], [25, 45]],
        ];
    }
}
