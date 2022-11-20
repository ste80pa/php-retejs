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

use Ste80pa\Retejs\Exception\MissingWorkerException;


/**
 *
 */
class Processor implements ProcessorInterface
{

    /**
     * @var \Ste80pa\Retejs\Engine\EngineResolverInterface
     */
    private $engineResolver;

    /**
     * @param \Ste80pa\Retejs\Engine\EngineResolverInterface $engineResolver
     */
    public function __construct(EngineResolverInterface $engineResolver)
    {
        $this->engineResolver = $engineResolver;
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Rete $rete
     * @param $userData
     * @return \Ste80pa\Retejs\Engine\Context
     * @throws \Ste80pa\Retejs\Exception\NoStartNodeException
     * @throws \Ste80pa\Retejs\Exception\NodeNotFoundException
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     * @throws \Ste80pa\Retejs\Exception\MissingWorkerException
     */
    public function process(Rete $rete, $userData): Context
    {
        $engine = $this->engineResolver->get($rete);

        $context = new Context($engine, $rete, $userData);
        $start = $rete->getStartNode();


        $this->backProcess($start, $context);
        $this->forwardProcess($start, $context);

        // Output will be in ?
        // array_pop($this->nodesOutput)
        return $context;
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Node $node
     * @param \Ste80pa\Retejs\Engine\ContextInterface $context
     * @return mixed
     * @throws \Ste80pa\Retejs\Exception\MissingWorkerException
     * @throws \Ste80pa\Retejs\Exception\NodeNotFoundException
     */
    public function backProcess(Node $node, ContextInterface $context)
    {
        if ($context->isTerminator($node)) {
            return $context->getNodeOutput($node);
        }

        if ($node->getWorker() === null) {
            throw new MissingWorkerException("Node with id '{$node->getId()}' has no worker");
        }

        $inputs = [];

        foreach ($node->getInputs() as $id => $input) {
            $outputData = [];
            foreach ($input->getConnections() as $connection) {
                // TODO: check
                $output = $this->backProcess($context->getRete()->getNode($connection), $context);
                if (!isset($output[$connection->getOutput()])) {
                    continue;
                }
                $outputData[] = $output[$connection->getOutput()];
            }

            $inputs[$id] = $outputData;
        }

        return $context->execute($node, $inputs);
    }

    /**
     * @param \Ste80pa\Retejs\Engine\Node $node
     * @param \Ste80pa\Retejs\Engine\ContextInterface $context
     * @return void
     * @throws \Ste80pa\Retejs\Exception\MissingWorkerException
     * @throws \Ste80pa\Retejs\Exception\NodeNotFoundException
     */
    public function forwardProcess(Node $node, ContextInterface $context)
    {
        foreach ($node->getOutputs() as $output) {
            foreach ($output->getConnections() as $connection) {
                $this->backProcess($context->getRete()->getNode($connection), $context);
                $this->forwardProcess($context->getRete()->getNode($connection), $context);
            }
        }
    }
}
