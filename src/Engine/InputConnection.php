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
class InputConnection
{
    /**
     * @var int|string|null
     */
    private $node;
    /**
     * @var int|string|null
     */
    private $output;

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
     * @param int|string|null $node
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
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param int|string $output
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     */
    public function setOutput($output)
    {
        if (!in_array(gettype($output), ['integer', 'string'])) {
            throw new InvalidInputTypeException(
                'Invalid \'output\' type provided only string or integer value are accepted'
            );
        }
        $this->output = $output;
    }

    /**
     * @param array $data
     * @return $this
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function fromArray(array $data): InputConnection
    {
        if (!isset($data['output'])) {
            throw new MissingParameterException('Missing output parameter');
        }

        if (!isset($data['node'])) {
            throw new MissingParameterException('Missing node parameter');
        }

        $this->setOutput($data['output']);
        $this->setNode($data['node']);
        $this->setData($data['data']??[]);
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * @param array| null $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data ?? [];
    }
}
