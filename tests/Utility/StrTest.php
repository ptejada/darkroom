<?php

namespace Darkroom\Tests\Utility;

use Darkroom\Utility\Str;

class StrTest extends \PHPUnit_Framework_TestCase
{
    public function patternProvider()
    {
        return [
            ['%6-%4-%6', '/^\w{6}-\w{4}-\w{6}$/'],
            ['%16', '/^\w{16}$/'],
            ['Y-m-d', '/^\d{4}-\d{2}-\d{2}$/'],
        ];
    }

    /**
     * @dataProvider patternProvider()
     *
     * @param string $pattern      The pattern
     * @param string $regExMatcher RegEx to validate output
     */
    public function testName($pattern, $regExMatcher)
    {
        $this->assertRegExp($regExMatcher, Str::name($pattern));
    }

    /**
     * @throws \Exception If it was not possible to gather sufficient entropy.
     */
    public function testRandomness()
    {
        $list = [];

        for($i = 0; $i < 5 ; $i++){
            $str = Str::random();
            $this->assertNotContains($str, $list);
            $list[] = $str;
        }
    }
}
