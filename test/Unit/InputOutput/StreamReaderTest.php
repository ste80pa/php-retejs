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

namespace Ste80pa\Retejs\Test\Unit\InputOutput;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Exception\InputOutputException;
use Ste80pa\Retejs\InputOutput\StreamReader;

/**
 *
 */
class StreamReaderTest extends TestCase
{
    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @return void
     */
    public function testEmptyStreamRead()
    {
        $handle = fopen('php://memory', 'rwb');
        fwrite($handle, '');
        fseek($handle, 0);


        $streamReader = new StreamReader();

        $this->expectException(InputOutputException::class);
        $streamReader->read($handle);
    }

    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @return void
     */
    public function testNullStreamRead()
    {
        $handle = fopen('php://memory', 'rwb');
        @fwrite($handle, null);
        fseek($handle, 0);


        $streamReader = new StreamReader();

        $this->expectException(InputOutputException::class);
        $streamReader->read($handle);
    }

    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @return void
     */
    public function testInvalidJsonStreamRead()
    {
        $handle = fopen('php://memory', 'rwb');
        fwrite($handle, '{ "id": "demo@0.1.0", "nodes": [}');
        fseek($handle, 0);

        $streamReader = new StreamReader();


        $this->expectException(InputOutputException::class);

        $streamReader->read($handle);
    }

    /**
     *
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InputOutputException
     */
    public function testValidJsonStreamRead()
    {
        $handle = fopen('php://memory', 'rwb');
        fwrite($handle, '{ "id": "demo@0.1.0", "nodes": []}');
        fseek($handle, 0);

        $streamReader = new StreamReader();

        $data = $streamReader->read($handle);

        self::assertEquals(['id' => 'demo@0.1.0', 'nodes' => []], $data);
    }
}
