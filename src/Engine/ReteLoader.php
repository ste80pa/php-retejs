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

use Ste80pa\Retejs\Exception\MalformedReteJsException;

/**
 *
 */
class ReteLoader
{
    /**
     * @param array $data
     * @return \Ste80pa\Retejs\Engine\Rete
     * @throws \Ste80pa\Retejs\Exception\MalformedReteJsException
     */
    public function load(array $data): Rete
    {
        $nodes = [];

        if (!isset($data['id'])) {
            throw new MalformedReteJsException("Missing property 'id'");
        }

        if (!isset($data['nodes'])) {
            throw new MalformedReteJsException("Missing property 'nodes'");
        }

        list($name, $version) = explode('@', $data['id']);

        foreach ($data['nodes'] as $nodeData) {
            $node = new Node();
            $node->fromArray($nodeData);
            $nodes[$node->getId()] = $node;
        }

        return new Rete($name, $version, $nodes);
    }
}
