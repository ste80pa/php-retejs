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
use Ste80pa\Retejs\Engine\OutputConnection;
use Ste80pa\Retejs\Exception\InvalidInputTypeException;
use Ste80pa\Retejs\Exception\MissingParameterException;

use function is_array;
use function is_string;

/**
 * @covers       \Ste80pa\Retejs\Engine\OutputConnection
 * @covers       \Ste80pa\Retejs\Engine\OutputConnection::fromArray
 * @covers       \Ste80pa\Retejs\Engine\OutputConnection::setInput
 * @covers       \Ste80pa\Retejs\Engine\OutputConnection::getInput
 * @covers       \Ste80pa\Retejs\Engine\OutputConnection::getNode
 */
class OutputConnectionTest extends TestCase
{


    /**
     * @covers       \Ste80pa\Retejs\Engine\OutputConnection::fromArray
     * @covers       \Ste80pa\Retejs\Engine\OutputConnection::setInput
     * @covers       \Ste80pa\Retejs\Engine\OutputConnection::getInput
     * @covers       \Ste80pa\Retejs\Engine\OutputConnection::getNode
     * @dataProvider fromArrayDataProvider
     * @throws \Exception
     */
    public function testFromArray($data, $expected)
    {
        $outputConnection = new OutputConnection();

        if (is_string($expected)) {
            $this->expectException($expected);
        }

        $outputConnection->fromArray($data);

        if (is_array($expected)) {
            static::assertEquals($expected['node'], $outputConnection->getNode());
            static::assertEquals($expected['input'], $outputConnection->getInput());
            static::assertEquals($expected['data'], $outputConnection->getData());
        }
    }


    /**
     * @return \array[][]
     */
    public function fromArrayDataProvider()
    {
        return [

            // Test 1
            [
                [
                    'input' => 1,
                    'node'  => 2,
                    'data'  => null
                ],
                [
                    'input' => 1,
                    'node'  => 2,
                    'data'  => []
                ],

            ],
            // Test 2
            [
                [
                    'node' => 2,
                    'data' => null
                ],
                MissingParameterException::class

            ],
            // Test 3
            [
                [
                    'input' => [],
                    'node'  => 2,
                    'data'  => null
                ],
                InvalidInputTypeException::class

            ],
            // Test 4
            [
                [
                    'input' => null,
                    'node'  => 2,
                    'data'  => null
                ],
                MissingParameterException::class

            ],

            // Test 5
            [
                [
                    'node' => 2,
                    'data' => null
                ],
                MissingParameterException::class

            ],
            // Test 6
            [
                [
                    'input' => 'input1',
                    'data'  => null
                ],
                MissingParameterException::class

            ],
            // Test 7
            [
                [
                    'input' => 'input1',
                    'node'  => [],
                    'data'  => null
                ],
                InvalidInputTypeException::class

            ],

        ];
    }
}
