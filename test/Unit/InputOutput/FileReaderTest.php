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

namespace Ste80pa\Retejs\Test\Unit\InputOutput;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Exception\FileNotFoundException;
use Ste80pa\Retejs\Exception\InputOutputException;
use Ste80pa\Retejs\InputOutput\FileReader;

/**
 * @covers \Ste80pa\Retejs\InputOutput\FileReader
 */
class FileReaderTest extends TestCase
{

    /**
     * @covers \Ste80pa\Retejs\InputOutput\FileReader::read
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InputOutputException
     */
    public function testNonExistingFile()
    {
        $fileReader = new FileReader();
        $this->expectException(FileNotFoundException::class);
        $fileReader->read('non_existing-file');
    }


    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @covers \Ste80pa\Retejs\InputOutput\FileReader::read
     * @return void
     * @throws \Ste80pa\Retejs\Exception\FileNotFoundException
     */
    public function testEmptyFile()
    {
        $fileReader = new FileReader();
        $this->expectException(InputOutputException::class);
        $fileReader->read(__DIR__ . '/files/empty.json');
    }

    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @covers \Ste80pa\Retejs\InputOutput\FileReader::read
     * @return void
     * @throws \Ste80pa\Retejs\Exception\FileNotFoundException
     */
    public function testMalformedFile()
    {
        $fileReader = new FileReader();
        $this->expectException(InputOutputException::class);
        $fileReader->read(__DIR__ . '/files/malformed.json');
    }

    /**
     * @covers \Ste80pa\Retejs\InputOutput\StreamReader::read
     * @covers \Ste80pa\Retejs\InputOutput\FileReader::read
     * @return void
     * @throws \Ste80pa\Retejs\Exception\FileNotFoundException
     * @throws \Ste80pa\Retejs\Exception\InputOutputException
     */
    public function testRead()
    {
        $fileReader = new FileReader();
        $data = $fileReader->read(__DIR__ . '/files/demo.json');
        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('nodes', $data);
        static::assertArrayHasKey('groups', $data);
        static::assertCount(3, $data['nodes']);
    }
}
