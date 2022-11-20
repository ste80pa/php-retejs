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

use JsonException;
use Ste80pa\Retejs\Exception\InputOutputException;

/**
 *
 */
class StreamReader implements ReaderInterface
{

    /**
     * @param $resource
     * @return array|mixed
     * @throws \Ste80pa\Retejs\Exception\InputOutputException
     */
    public function read($resource)
    {
        $json = stream_get_contents($resource);

        if ($json === false) {
            throw new InputOutputException('Unable to read form stream');
        }

        if ($json === '') {
            throw new InputOutputException('Empty stream');
        }

        if (PHP_VERSION_ID >= 70300) {
            try {
                return (array)json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }catch (JsonException $exception){
                throw new InputOutputException(
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception
                );
            }
        }

        $data = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        throw new InputOutputException(json_last_error_msg());
    }
}
