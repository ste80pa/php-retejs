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

namespace Ste80pa\Retejs\Test\Unit\Engine;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Engine\Engine;
use Ste80pa\Retejs\Engine\VersionCheckerInterface;
use Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface;


/**
 * @covers \Ste80pa\Retejs\Engine\Engine
 */
class EngineTest extends TestCase
{

    /**
     * @covers \Ste80pa\Retejs\Engine\Engine::__construct
     * @covers \Ste80pa\Retejs\Engine\Engine::getId
     * @covers \Ste80pa\Retejs\Engine\Engine::getName
     * @covers \Ste80pa\Retejs\Engine\Engine::getVersion
     * @covers \Ste80pa\Retejs\Engine\Engine::getWorkers
     * @covers \Ste80pa\Retejs\Engine\Engine::supports
     * @return void
     */
    public function testEngine()
    {
        $versionChecker = $this->getMockBuilder(VersionCheckerInterface::class)
            ->getMock();

        $versionChecker
            ->method('isCompatible')
            ->will(static::onConsecutiveCalls(true, false));

        $workerPool = $this->getMockBuilder(WorkerPoolInterface::class)
            ->getMock();
        $engine = new Engine('name', '0.1.0', $versionChecker, $workerPool);

        static::assertEquals('name@0.1.0', $engine->getId());
        static::assertEquals('name', $engine->getName());
        static::assertEquals('0.1.0', $engine->getVersion());
        static::assertEquals($workerPool, $engine->getWorkers());
        static::assertEquals(true, $engine->supports($engine));
        static::assertEquals(false, $engine->supports($engine));
    }


}
