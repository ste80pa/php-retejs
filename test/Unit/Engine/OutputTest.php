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
use Ste80pa\Retejs\Engine\Output;

use function count;

/**
 * @covers  \Ste80pa\Retejs\Engine\Output
 * @covers  \Ste80pa\Retejs\Engine\OutputConnection
 */
class OutputTest extends TestCase
{
    /**
     * @covers \Ste80pa\Retejs\Engine\Output::getConnections
     * @covers \Ste80pa\Retejs\Engine\Output::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::fromArray
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setData
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setNode
     * @covers \Ste80pa\Retejs\Engine\OutputConnection::setInput
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function testFromArray()
    {
        $data = [
            'connections' =>
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
                    ]
                ]

        ];
        $output = new Output();
        $output->fromArray($data);
        $connections = $output->getConnections();

        self::assertCount(count($data['connections']), $connections);
    }
}
