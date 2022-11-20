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
use stdClass;
use Ste80pa\Retejs\Engine\InputConnection;
use Ste80pa\Retejs\Exception\InvalidInputTypeException;
use Ste80pa\Retejs\Exception\MissingParameterException;

use function is_array;
use function is_string;

/**
 * @covers \Ste80pa\Retejs\Engine\InputConnection
 */
class InputConnectionTest extends TestCase
{
    /**
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setNode
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function testInvalidOutputSet()
    {
        $inputConnection = new InputConnection();
        $this->expectException(InvalidInputTypeException::class);
        $inputConnection->setOutput(new stdClass());
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\InputConnection::setNode
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function testInvalidNodeSet()
    {
        $inputConnection = new InputConnection();
        $this->expectException(InvalidInputTypeException::class);
        $inputConnection->setNode(new stdClass());
    }

    /**
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::fromArray
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::setOutput
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::getData
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::getOutput
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::getNode
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::setData
     * @covers       \Ste80pa\Retejs\Engine\InputConnection::setNode
     * @dataProvider fromArrayDataProvider
     * @throws \Exception
     */
    public function testFromArray($data, $expected)
    {
        $inputConnection = new InputConnection();

        if (is_string($expected)) {
            $this->expectException($expected);
        }

        $inputConnection->fromArray($data);

        if (is_array($expected)) {
            static::assertEquals($expected['node'], $inputConnection->getNode());
            static::assertEquals($expected['output'], $inputConnection->getOutput());
            static::assertEquals($expected['data'], $inputConnection->getData());
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
                    'output' => 1,
                    'node'   => 2,
                    'data'   => null
                ],
                [
                    'output' => 1,
                    'node'   => 2,
                    'data'   => []
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
                    'output' => [],
                    'node'   => 2,
                    'data'   => null
                ],
                InvalidInputTypeException::class

            ],
            // Test 4
            [
                [
                    'output' => null,
                    'node'   => 2,
                    'data'   => null
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

            // Test 5
            [
                [
                    'data' => null,
                    'output' => 1,
                ],
                MissingParameterException::class

            ],

        ];
    }
}
