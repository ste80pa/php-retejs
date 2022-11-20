<?php
/**
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

use Ste80pa\Retejs\Exception\NodeNotFoundException;
use Ste80pa\Retejs\Exception\NoStartNodeException;

use function is_a;
use function is_int;
use function is_string;

/**
 *
 */
class Rete implements VersionedInterface
{

    /**
     * Id string composed bu 2 parts name and version joined by the character '@'.
     * Example demo@1.0.0
     * @var string
     */
    private $id;
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $version;

    /**
     * @var Node[]
     */
    private $nodes;


    /**
     * @param $name
     * @param $version
     * @param $nodes
     */
    public function __construct($name, $version, $nodes)
    {
        $this->name = $name;
        $this->version = $version;
        $this->id = "$name@$version";
        $this->nodes = $nodes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param int|string|Node|InputConnection|OutputConnection $element
     * @return Node
     * @throws \Ste80pa\Retejs\Exception\NodeNotFoundException
     */
    public function getNode($element): Node
    {
        $nodeId = null;

        if (is_string($element) || is_int($element)) {
            $nodeId = $element;
        }

        if (is_a($element, InputConnection::class) | is_a($element, OutputConnection::class)) {
            $nodeId = $element->getNode();
        }

        if (is_a($element, Node::class)) {
            $nodeId = $element->getId();
        }

        if (isset($this->nodes[$nodeId])) {
            return $this->nodes[$nodeId];
        }

        throw new NodeNotFoundException("Unable to find node with id '$nodeId'");
    }

    /**
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Node
     * @throws \Ste80pa\Retejs\Exception\NoStartNodeException
     */
    public function getStartNode(): Node
    {
        if(empty($this->nodes)) {
            throw new NoStartNodeException('No start node found');
        }

        return array_values($this->nodes)[0];
    }

    /**
     * @return array|null
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
