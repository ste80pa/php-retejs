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
use Ste80pa\Retejs\Engine\EngineResolver;
use Ste80pa\Retejs\Engine\VersionCheckerInterface;
use Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface;
use Ste80pa\Retejs\Exception\EngineNotFoundException;

/**
 * @covers \Ste80pa\Retejs\Engine\EngineResolver
 */
class EngineResolverTest extends TestCase
{
    /**
     * @covers \Ste80pa\Retejs\Engine\Engine::__construct
     * @covers \Ste80pa\Retejs\Engine\Engine::getName
     * @covers \Ste80pa\Retejs\Engine\Engine::getVersion
     * @covers \Ste80pa\Retejs\Engine\Engine::supports
     * @return void
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testEngineResolverGet()
    {
        $versionChecker = $this->getMockBuilder(VersionCheckerInterface::class)
            ->getMock();
        $workerPool = $this->getMockBuilder(WorkerPoolInterface::class)
            ->getMock();
        $versionChecker
            ->method('isCompatible')
            ->willReturn(false);

        $engine1 = new Engine('engine1', '0.1.0', $versionChecker, $workerPool);

        $resolver = new EngineResolver();
        $resolver->add($engine1);

        $this->expectException(EngineNotFoundException::class);
        $resolver->get($engine1);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\Engine::__construct
     * @covers \Ste80pa\Retejs\Engine\Engine::getName
     * @covers \Ste80pa\Retejs\Engine\Engine::getVersion
     * @covers \Ste80pa\Retejs\Engine\Engine::supports
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testEngineResolver()
    {
        $versionChecker = $this->getMockBuilder(VersionCheckerInterface::class)
            ->getMock();
        $workerPool = $this->getMockBuilder(WorkerPoolInterface::class)
            ->getMock();
        $versionChecker
            ->method('isCompatible')
            ->willReturnCallback(static function ($a, $b) {
                return ($a->getName() === $b->getName()) && version_compare($a->getVersion(), $b->getVersion(), '>=');
            });

        $engine0 = new Engine('engine1', '0.0.0', $versionChecker, $workerPool);
        $engine1 = new Engine('engine1', '0.1.0', $versionChecker, $workerPool);
        $engine2 = new Engine('engine2', '0.1.0', $versionChecker, $workerPool);
        $engine3 = new Engine('engine3', '0.1.0', $versionChecker, $workerPool);
        $engine4 = new Engine('engine1', '4.0.0', $versionChecker, $workerPool);

        $resolver = new EngineResolver();
        $resolver
            ->add($engine1)
            ->add($engine2);

        self::assertEquals($engine1, $resolver->get($engine1));
        self::assertEquals($engine2, $resolver->get($engine2));

        self::assertTrue($resolver->has($engine1));
        self::assertTrue($resolver->has($engine2));
        self::assertFalse($resolver->has($engine3));
        self::assertFalse($resolver->has($engine4));
        self::assertTrue($resolver->has($engine0));

        $this->expectException(EngineNotFoundException::class);
        $resolver->get($engine3);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\Engine::__construct
     * @covers \Ste80pa\Retejs\Engine\EngineResolver::add
     * @covers \Ste80pa\Retejs\Engine\Engine::supports
     * @covers \Ste80pa\Retejs\Engine\Engine::getName
     * @covers \Ste80pa\Retejs\Engine\EngineResolver::get
     * @covers \Ste80pa\Retejs\Engine\Engine::getVersion
     * @return void
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testEngineAdd()
    {
        $versionChecker = $this->getMockBuilder(VersionCheckerInterface::class)
            ->getMock();
        $workerPool = $this->getMockBuilder(WorkerPoolInterface::class)
            ->getMock();
        $versionChecker
            ->method('isCompatible')
            ->willReturnCallback(static function ($a, $b) {
                return ($a->getName() === $b->getName());
            });

        $engine1 = new Engine('engine', '0.1.1', $versionChecker, $workerPool);
        $engine2 = new Engine('engine', '0.2.0', $versionChecker, $workerPool);
        $engine3 = new Engine('engine', '1.1.0', $versionChecker, $workerPool);
        $resolver = new EngineResolver();
        $resolver
            ->add($engine1)
            ->add($engine2)
            ->add($engine3);

        $versions = $resolver->getAll();

        self::assertArrayHasKey('engine', $versions);
        self::assertNotEmpty($versions['engine']);
        self::assertEquals(['1.1.0', '0.2.0', '0.1.1'], array_keys($versions['engine']));

        $engine = $resolver->get($engine1);

        self::assertEquals($engine3, $engine);
    }
}
