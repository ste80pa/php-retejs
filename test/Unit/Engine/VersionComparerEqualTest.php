<?php
/*
 * Copyright (c) 2022 Stefano Pallozzi
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Ste80pa\Retejs\Test\Unit\Engine;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Engine\Engine;
use Ste80pa\Retejs\Engine\VersionComparerEqual;

/**
 * @covers        \Ste80pa\Retejs\Engine\VersionComparerEqual
 */
class VersionComparerEqualTest extends TestCase
{
    /**
     * @covers         \Ste80pa\Retejs\Engine\VersionComparerEqual::isCompatible
     * @return void
     */
    public function testIsCompatible()
    {
        $engine1 = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engine2 = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engine3 = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $engine4 = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->getMock();

        $engine1->method('getVersion')->willReturn('0.1.0');
        $engine1->method('getName')->willReturn('demo');
        $engine1->method('getId')->willReturn('demo@0.1.0');

        $engine2->method('getVersion')->willReturn('0.1.1');
        $engine2->method('getName')->willReturn('demo');
        $engine2->method('getId')->willReturn('demo@0.1.1');

        $engine3->method('getVersion')->willReturn('0.0.9');
        $engine3->method('getName')->willReturn('demo');
        $engine3->method('getId')->willReturn('demo@0.0.9');

        $engine4->method('getVersion')->willReturn('0.1.0');
        $engine4->method('getName')->willReturn('abcd');
        $engine4->method('getId')->willReturn('abcd@0.1.0');

        $comparator = new VersionComparerEqual();


        self::assertTrue($comparator->isCompatible($engine1, $engine1));
        self::assertFalse($comparator->isCompatible($engine2, $engine1));
        self::assertFalse($comparator->isCompatible($engine3, $engine1));
        self::assertFalse($comparator->isCompatible($engine4, $engine1));
    }
}
