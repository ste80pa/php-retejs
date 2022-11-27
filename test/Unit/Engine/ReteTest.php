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
use Ste80pa\Retejs\Engine\InputConnection;
use Ste80pa\Retejs\Engine\Node;
use Ste80pa\Retejs\Engine\OutputConnection;
use Ste80pa\Retejs\Engine\Rete;
use Ste80pa\Retejs\Exception\NodeNotFoundException;
use Ste80pa\Retejs\Exception\NoStartNodeException;
use Throwable;

use const Ste80pa\Retejs\Exception\NoStartNodeException;

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
    public function testRete()
    {
        $node1 = $this->getMockBuilder(Node::class)->getMock();
        $node2 = $this->getMockBuilder(Node::class)->getMock();
        $node3 = $this->getMockBuilder(Node::class)->getMock();

        $node1->method('getId')->willReturn(1);
        $node2->method('getId')->willReturn(2);
        $node3->method('getId')->willReturn(3);
        $nodes = [1 => $node1, 2 => $node2, 3 => $node3];

        $rete = new Rete('demo', '0.0.0');

        foreach ($nodes as $node) {
            $rete->addNode($node);
        }

        self::assertEquals('demo', $rete->getName());
        self::assertEquals('0.0.0', $rete->getVersion());
        self::assertEquals('demo@0.0.0', $rete->getId());
        self::assertEquals($nodes, $rete->getNodes());
    }

    /**
     * @covers       \Ste80pa\Retejs\Engine\Rete::addNode
     * @dataProvider nodeProvider
     * @return void
     * @throws \Ste80pa\Retejs\Exception\NodeNotFoundException
     */
    public function testGetNode($nodes, $searchNode, $expected)
    {
        $rete = new Rete('demo', '0.0.0');

        foreach ($nodes as $node) {
            $rete->addNode($node);
        }

        if (is_a($expected, Throwable::class, true)) {
            $this->expectException(NodeNotFoundException::class);
            $rete->getNode($searchNode);
        } else {
            $found = $rete->getNode($searchNode);
            self::assertEquals($expected, $found);
            self::assertEquals($expected->getId(), $found->getId());

        }
    }

    /**
     * @return array
     */
    public function nodeProvider(): array
    {
        $node1 = $this->getMockBuilder(Node::class)->getMock();
        $node2 = $this->getMockBuilder(Node::class)->getMock();
        $node3 = $this->getMockBuilder(Node::class)->getMock();

        $node1->method('getId')->willReturn(1);
        $node2->method('getId')->willReturn(2);
        $node3->method('getId')->willReturn(3);

        $nodes = [1 => $node1, 2 => $node2, 3 => $node3];

        $inputConnection = $this->getMockBuilder(InputConnection::class)->getMock();
        $outputConnection = $this->getMockBuilder(OutputConnection::class)->getMock();

        $inputConnection->method('getNode')->willReturn(2);
        $outputConnection->method('getNode')->willReturn(2);

        return [

            [$nodes, 1, $node1],
            [$nodes, '1', $node1],
            [$nodes, $inputConnection, $node2],
            [$nodes, $outputConnection, $node2],
            [$nodes, $node3, $node3],
            [$nodes, 7, NodeNotFoundException::class],
            [[], '1', NodeNotFoundException::class]
        ];
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\Rete::getStartNode
     * @return void
     */
    public function testFailedWhenGettingStartNode()
    {
        $rete = new Rete('demo', '0.0.0');

        $this->expectException(NoStartNodeException::class);
        $rete->getStartNode();
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\Rete::getStartNode
     * @return void
     * @throws \Ste80pa\Retejs\Exception\NoStartNodeException
     */
    public function testStartNode()
    {

        $node1 = $this->getMockBuilder(Node::class)->getMock();
        $node2 = $this->getMockBuilder(Node::class)->getMock();
        $node3 = $this->getMockBuilder(Node::class)->getMock();

        $node1->method('getId')->willReturn(1);
        $node2->method('getId')->willReturn(2);
        $node3->method('getId')->willReturn(3);

        $rete = new Rete('demo', '0.0.0');

        $rete->addNode($node3);
        $rete->addNode($node2);
        $rete->addNode($node1);

        self::assertEquals($node3, $rete->getStartNode());
        self::assertEquals($node3->getId(), $rete->getStartNode()->getId());
    }
}
