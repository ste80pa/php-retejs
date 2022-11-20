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

use function array_key_exists;
use function count;

/**
 *
 */
class Context implements ContextInterface
{
    /**
     * @var mixed
     */
    private $userData;

    /**
     * @var \Ste80pa\Retejs\Engine\EngineInterface
     */
    private $engine;

    /**
     * @var array
     */
    private $outputs = [];

    /**
     * @var \Ste80pa\Retejs\Engine\Rete
     */
    private $rete;

    /**
     * @var Node | null
     */
    private $lastNode;

    /**
     * @param \Ste80pa\Retejs\Engine\EngineInterface $engine
     * @param \Ste80pa\Retejs\Engine\Rete $rete
     * @param $userData
     */
    public function __construct(EngineInterface $engine, Rete $rete, $userData)
    {
        $this->engine = $engine;
        $this->userData = $userData;
        $this->rete = $rete;
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Node $node
     * @return bool
     */
    public function isTerminator(Node $node): bool
    {
        $nodeId = $node->getId();
        return (isset($this->outputs[$nodeId]) && count($this->outputs[$nodeId]) === 1);
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Node $node
     * @return mixed
     */
    public function getNodeOutput(Node $node)
    {
        $nodeId = $node->getId();
        if (!array_key_exists($nodeId, $this->outputs)) {
            $this->outputs[$nodeId] = [];
        }
        return $this->outputs[$nodeId];
    }

    /**
     * @return array
     */
    public function getOutputs(): array
    {
        return $this->outputs ?? [];
    }

    /**
     * User defined context
     * @return mixed
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @return \Ste80pa\Retejs\Engine\EngineInterface
     */
    public function getEngine(): EngineInterface
    {
        return $this->engine;
    }

    /**
     * @return \Ste80pa\Retejs\Engine\Rete
     */
    public function getRete(): Rete
    {
        return $this->rete;
    }

    /**
     * @return mixed|null
     */
    public function getOutput(){
        if(!$this->lastNode || empty($this->outputs[$this->lastNode->getId()])) {
            return null;
        }

        return array_values($this->outputs[$this->lastNode->getId()])[0];
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Node $node
     * @param array $inputs
     * @return array|mixed
     */
    public function execute(Node $node, array $inputs)
    {
        $this->lastNode = $node;
        $nodeId = $node->getId();
        $worker = $this->engine->getWorkers()->get($node->getWorker());
        $this->outputs[$nodeId] = $worker->execute($this, $node, $inputs);
        return $this->outputs[$nodeId];
    }
}

