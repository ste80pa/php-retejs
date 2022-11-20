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

declare(strict_types=1);

namespace Ste80pa\Retejs\Test\Unit\Engine;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Engine\Node;
use Ste80pa\Retejs\Engine\Rete;

/**
 * @covers \Ste80pa\Retejs\Engine\Rete
 */
class ReteTest extends TestCase
{

    /**
     * @covers  \Ste80pa\Retejs\Engine\Rete::getNodes
     * @covers  \Ste80pa\Retejs\Engine\Rete::getVersion
     * @covers  \Ste80pa\Retejs\Engine\Rete::getName
     * @covers  \Ste80pa\Retejs\Engine\Rete::getId
     * @return void
     */
    public function testGetName()
    {
        $nodes = [
            $this->getMockBuilder(Node::class)->getMock(),
            $this->getMockBuilder(Node::class)->getMock(),
            $this->getMockBuilder(Node::class)->getMock()
        ];
        $rete = new Rete('demo', '0.0.0', $nodes);

        self::assertEquals('demo', $rete->getName());
        self::assertEquals('0.0.0', $rete->getVersion());
        self::assertEquals('demo@0.0.0', $rete->getId());
        self::assertEquals($nodes, $rete->getNodes());
    }

}
