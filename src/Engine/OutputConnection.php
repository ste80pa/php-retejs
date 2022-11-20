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

namespace Ste80pa\Retejs\Engine;

use Ste80pa\Retejs\Exception\InvalidInputTypeException;
use Ste80pa\Retejs\Exception\MissingParameterException;

use function gettype;
use function in_array;

/**
 *
 */
class OutputConnection
{
    /**
     * @var int|string|null
     */
    private $node;
    /**
     * @var int|string|null
     */
    private $input;
    /**
     * @var array|null
     */
    private $data = [];

    /**
     * @return int|string|null
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param int|string $node
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function setNode($node)
    {
        if (!in_array(gettype($node), ['integer', 'string'])) {
            throw new InvalidInputTypeException(
                'Invalid \'node\' type provided only string or integer value are accepted'
            );
        }
        $this->node = $node;
    }

    /**
     * @return int|string|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param int|string|null $input
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function setInput($input)
    {
        if (!in_array(gettype($input), ['integer', 'string'])) {
            throw new InvalidInputTypeException(
                'Invalid \'input\' type provided only string or integer value are accepted'
            );
        }
        $this->input = $input;
    }

    /**
     * @param array $data
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function fromArray(array $data): OutputConnection
    {
        if (!isset($data['input'])) {
            throw new MissingParameterException('Missing output parameter');
        }

        if (!isset($data['node'])) {
            throw new MissingParameterException('Missing node parameter');
        }

        $this->setInput($data['input']);
        $this->setNode($data['node']);
        $this->setData($data['data']??[]);
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     */
    public function setData($data)
    {
        $this->data = $data ?? [];
    }
}
