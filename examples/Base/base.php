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

namespace Ste80pa\Retejs\Examples;

include_once(__DIR__ . '/../../vendor/autoload.php');

use Ste80pa\Retejs\Engine\Engine;
use Ste80pa\Retejs\Engine\EngineResolver;
use Ste80pa\Retejs\Engine\Processor;
use Ste80pa\Retejs\Engine\ReteLoader;
use Ste80pa\Retejs\Engine\VersionComparerEqualOrGreater;
use Ste80pa\Retejs\Engine\Worker\WorkerPool;
use Ste80pa\Retejs\Examples\Base\Workers\Add;
use Ste80pa\Retejs\Examples\Base\Workers\Add2;
use Ste80pa\Retejs\Examples\Base\Workers\Number;
use Ste80pa\Retejs\Examples\Base\Workers\Number2;
use Ste80pa\Retejs\InputOutput\FileReader;


//
// Engines
//

$workerPool1 = (new WorkerPool())
    ->add('add', new Add())
    ->add('number', new Number());

$workerPool2 = (new WorkerPool())
    ->add('add', new Add2())
    ->add('number', new Number2());


$engine1 = new Engine('demo', '0.1.0', new VersionComparerEqualOrGreater(), $workerPool1);
$engine2 = new Engine('demo2', '0.1.0', new VersionComparerEqualOrGreater(), $workerPool2);

$engineResolver = (new EngineResolver())
    ->add($engine1)
    ->add($engine2);

$reader = new FileReader();
$loader = new ReteLoader();
$processor = new Processor($engineResolver);

$result = $processor->process(
    $loader->load($reader->read(__DIR__ . '/demo.json')),
    []);
echo 'the result is : ', $result->getOutput() . PHP_EOL;

$result = $processor->process(
    $loader->load($reader->read(__DIR__ . '/demo2.json')),
    []
);
echo 'the result is : ', $result->getOutput() . PHP_EOL;
