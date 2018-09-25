<?php

namespace Darkroom\Tests\Utility;

use Darkroom\Utility\Str;

class StrTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $this->assertRegExp('/\w{6}-\w{4}-\w{6}/', Str::name());
    }
}
