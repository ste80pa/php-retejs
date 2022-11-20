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

namespace Ste80pa\Retejs\InputOutput;

use Ste80pa\Retejs\Exception\FileNotFoundException;
use Ste80pa\Retejs\Exception\InputOutputException;
use Throwable;

/**
 *
 */
class FileReader extends StreamReader
{


    /**
     * @param $resource
     * @return array|mixed
     * @throws \Ste80pa\Retejs\Exception\FileNotFoundException
     * @throws \Ste80pa\Retejs\Exception\InputOutputException
     */
    public function read($resource)
    {
        if(!is_file($resource)){
            throw new FileNotFoundException("Could not open file '$resource'");
        }

        if(!is_readable($resource)){
            throw new InputOutputException("File '$resource' is not readable");
        }

        $stream = fopen($resource, 'rb');

        if ($stream === false) {
            throw new InputOutputException("Cannot read from '$resource'");
        }

        try {
            $output = parent::read($stream);
            fclose($stream);
            return $output;
        } catch (Throwable $throwable) {
            fclose($stream);
            throw $throwable;
        }
    }
}
