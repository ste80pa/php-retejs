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
use Ste80pa\Retejs\Engine\ReteLoader;
use Ste80pa\Retejs\Exception\MalformedReteJsException;

use function count;

/**
 * @covers \Ste80pa\Retejs\Engine\ReteLoader
 */
class ReteLoaderTest extends TestCase
{

    const RETE1 = [
        'id'     => 'demo@0.1.0',
        'nodes'  =>
            [
                1 =>
                    [
                        'id'       => 1,
                        'data'     =>
                            [
                                'num' => 3,
                            ],
                        'group'    => null,
                        'inputs'   =>
                            [
                            ],
                        'outputs'  =>
                            [
                                0 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'  => 3,
                                                        'input' => 0,
                                                    ],
                                                1 =>
                                                    [
                                                        'node'  => 3,
                                                        'input' => 1,
                                                    ],
                                            ],
                                    ],
                            ],
                        'position' =>
                            [
                                0 => -389.9999694824219,
                                1 => 183.3333282470703,
                            ],
                        'title'    => 'Number',
                    ],
                3 =>
                    [
                        'id'       => 3,
                        'data'     =>
                            [
                            ],
                        'group'    => null,
                        'inputs'   =>
                            [
                                0 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'   => 1,
                                                        'output' => 0,
                                                    ],
                                            ],
                                    ],
                                1 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'   => 1,
                                                        'output' => 0,
                                                    ],
                                            ],
                                    ],
                            ],
                        'outputs'  =>
                            [
                                0 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'  => 4,
                                                        'input' => 0,
                                                    ],
                                            ],
                                    ],
                            ],
                        'position' =>
                            [
                                0 => 507.77777099609375,
                                1 => 246.66665649414062,
                            ],
                        'title'    => 'Add',
                    ],
                4 =>
                    [
                        'id'       => 4,
                        'data'     =>
                            [
                            ],
                        'group'    => null,
                        'inputs'   =>
                            [
                                0 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'   => 1,
                                                        'output' => 0,
                                                    ],
                                            ],
                                    ],
                                1 =>
                                    [
                                        'connections' =>
                                            [
                                                0 =>
                                                    [
                                                        'node'   => 3,
                                                        'output' => 0,
                                                    ],
                                            ],
                                    ],
                            ],
                        'outputs'  =>
                            [
                                0 =>
                                    [
                                        'connections' =>
                                            [
                                            ],
                                    ],
                            ],
                        'position' =>
                            [
                                0 => 25.888877391815186,
                                1 => 227.66665649414062,
                            ],
                        'title'    => 'Add',
                    ],
            ],
        'groups' =>
            [
            ],
    ];

    /**
     * @covers \Ste80pa\Retejs\Engine\ReteLoader::load
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MalformedReteJsException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function testLoadMissingId()
    {
        $loader = new ReteLoader();
        $this->expectException(MalformedReteJsException::class);
        $loader->load(['nodes' => []]);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\ReteLoader::load
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MalformedReteJsException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function testLoadMissingNodes()
    {
        $loader = new ReteLoader();
        $this->expectException(MalformedReteJsException::class);
        $loader->load(['id' => 1]);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\ReteLoader::load
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MalformedReteJsException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function testLoadMalformedInput()
    {
        $loader = new ReteLoader();
        $this->expectException(MalformedReteJsException::class);
        $loader->load(['id', 'nodes']);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\Input::fromArray
     * @covers \Ste80pa\Retejs\Engine\InputConnection::fromArray
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setData
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setNode
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setOutput
     * @covers \Ste80pa\Retejs\Engine\Node::fromArray
     * @covers \Ste80pa\Retejs\Engine\Node::getId
     * @covers \Ste80pa\Retejs\Engine\Node::getName
     * @covers \Ste80pa\Retejs\Engine\Output::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setData
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setInput
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setNode
     * @covers \Ste80pa\Retejs\Engine\Rete::__construct
     * @covers \Ste80pa\Retejs\Engine\Rete::getId
     * @covers \Ste80pa\Retejs\Engine\Rete::getNodes
     * @covers \Ste80pa\Retejs\Engine\ReteLoader::load
     * @covers \Ste80pa\Retejs\Engine\Rete::addNode
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MalformedReteJsException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function testLoad()
    {
        $loader = new ReteLoader();
        $rete = $loader->load(self::RETE1);

        self::assertEquals('demo@0.1.0', $rete->getId());
        self::assertCount(count(self::RETE1['nodes']), $rete->getNodes());
    }

}
