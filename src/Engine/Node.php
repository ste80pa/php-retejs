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

/**
 *
 */
class Node
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $group;
    /**
     * @var int[]
     */
    private $position = [0, 0];
    /**
     * @var Input[]
     */
    private $inputs = [];
    /**
     * @var array
     */
    private $outputs = [];
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var string|null
     */
    private $worker;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return int[]
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    /**
     * @return \Ste80pa\Retejs\Engine\Input[]
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }


    /**
     * @return \Ste80pa\Retejs\Engine\Output[]
     */
    public function getOutputs()
    {
        return $this->outputs;
    }


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @param array $data
     * @return void
     * @throws \Ste80pa\Retejs\Exception\InvalidInputTypeException
     * @throws \Ste80pa\Retejs\Exception\MissingParameterException
     */
    public function fromArray(array $data)
    {
        $this->id = $data['id'];

        if (isset($data['name'])) {
            $this->name = $data['name'];
        }

        if (isset($data['title'])) {
            $this->name = $data['title'];
        }

        $this->data = $data['data'];

        if (isset($data['group'])) {
            $this->group = $data['group'];
        }

        $this->worker = str_replace(' ', '_', strtolower($this->getName()));
        $this->position = $data['position'];

        $this->outputs = [];
        foreach ($data['outputs'] as $outputId => $outputData) {
            $output = new Output();
            $output->fromArray($outputData);
            $this->outputs[$outputId] = $output;
        }

        $this->inputs = [];
        foreach ($data['inputs'] as $inputId => $inputData) {
            $input = new Input();
            $input->fromArray($inputData);
            $this->inputs[$inputId] = $input;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getWorker()
    {
        return $this->worker;
    }

}
