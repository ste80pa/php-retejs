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
use Ste80pa\Retejs\Engine\Input;
use Ste80pa\Retejs\Engine\Node;
use Ste80pa\Retejs\Engine\Output;

/**
 *
 */
class NodeTest extends TestCase
{

    /**
     * @covers \Ste80pa\Retejs\Engine\Node::getData
     * @covers \Ste80pa\Retejs\Engine\Node::getId
     * @covers \Ste80pa\Retejs\Engine\Node::getOutputs
     * @covers \Ste80pa\Retejs\Engine\Node::getWorker
     * @covers \Ste80pa\Retejs\Engine\Node::getName
     * @covers \Ste80pa\Retejs\Engine\Output::fromArray
     * @covers \Ste80pa\Retejs\Engine\Node::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setData
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setInput
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setNode
     * @covers \Ste80pa\Retejs\Engine\Input::fromArray
     * @covers \Ste80pa\Retejs\Engine\InputConnection::fromArray
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setData
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setNode
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setOutput
     * @covers \Ste80pa\Retejs\Engine\Node::getGroup
     * @covers \Ste80pa\Retejs\Engine\Node::getInputs
     * @covers \Ste80pa\Retejs\Engine\Node::getPosition
     * @dataProvider nodeProvider
     * @param $data
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function testFromArray($data)
    {
        $node = new Node();
        $node->fromArray($data);

        self::assertEquals('Number Node', $node->getName());
        self::assertEquals(1, $node->getId());
        self::assertEquals(['num' => 3], $node->getData());
        self::assertEquals([1, 2], $node->getPosition());
        self::assertEquals('number_node', $node->getWorker());
        self::assertEquals('group', $node->getGroup());
        $outputs = $node->getOutputs();
        $inputs = $node->getInputs();
        self::assertCount(1, $outputs);
        self::assertContainsOnlyInstancesOf(Output::class, $outputs);
        self::assertContainsOnlyInstancesOf(Input::class, $inputs);
    }

    /**
     * @return array[]
     */
    public function nodeProvider()
    {
        return [

            // Legacy node

            [
                [
                    'id'       => 1,
                    'data'     => [
                        'num' => 3
                    ],
                    'group'    => 'group',
                    'inputs'   => [

                        [
                            'connections' => [
                                [
                                    'node'   => 1,
                                    'output' => 0
                                ]
                            ]
                        ]
                    ],
                    'outputs'  => [
                        [
                            'connections' => [
                                [
                                    'node'  => 3,
                                    'input' => 0
                                ],
                                [
                                    'node'  => 3,
                                    'input' => 1
                                ]
                            ]
                        ]
                    ],
                    'position' => [1, 2],
                    'title'    => 'Number Node'
                ]
            ],

            // Node

            [
                [
                    'id'       => 1,
                    'data'     => [
                        'num' => 3
                    ],
                    'group'    => 'group',
                    'inputs'   => [
                        [
                            'connections' => [
                                [
                                    'node'   => 1,
                                    'output' => 0
                                ]
                            ]
                        ]
                    ],
                    'outputs'  => [
                        [
                            'connections' => [
                                [
                                    'node'  => 3,
                                    'input' => 0
                                ],
                                [
                                    'node'  => 3,
                                    'input' => 1
                                ]
                            ]
                        ]
                    ],
                    'position' => [1, 2],
                    'name'     => 'Number Node'
                ]
            ]


        ];
    }
}
