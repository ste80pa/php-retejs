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

namespace Ste80pa\Retejs\Test\Unit\Engine;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Engine\EngineInterface;
use Ste80pa\Retejs\Engine\StrictEngineResolver;
use Ste80pa\Retejs\Exception\EngineNotFoundException;

/**
 * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver
 * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::has
 * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::add
 * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::get
 */
class StrictEngineResolverTest extends TestCase
{
    /**
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::has
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::add
     * @return void
     */
    public function testHas()
    {
        $engine1 = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine1->method('getId')
            ->willReturn('engine1@0.0.1');

        $engine2 = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine2->method('getId')
            ->willReturn('engine1@0.0.2');

        $resolver = new StrictEngineResolver();
        $resolver->add($engine1);

        self::assertEquals(true, $resolver->has($engine1));
        self::assertEquals(false, $resolver->has($engine2));
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::get
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::add
     * @return void
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testGetFails()
    {
        $engine1 = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine1->method('getId')
            ->willReturn('engine1@0.0.1');

        $resolver = new StrictEngineResolver();

        $this->expectException(EngineNotFoundException::class);
        $resolver->get($engine1);
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::get
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::add
     * @return void
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testGetSucceeds()
    {
        $engine1 = $this->getMockBuilder(EngineInterface::class)->getMock();
        $engine1->method('getId')
            ->willReturn('engine1@0.0.1');

        $engine2 = $this->getMockBuilder(EngineInterface::class)->getMock();

        $engine2->method('getId')
            ->willReturn('engine1@0.0.2');

        $resolver = new StrictEngineResolver();
        $resolver->add($engine1);
        static::assertEquals($engine1, $resolver->get($engine1));
        $resolver->add($engine2);
        static::assertEquals($engine2, $resolver->get($engine2));
    }

    /**
     * @covers \Ste80pa\Retejs\Engine\StrictEngineResolver::add
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function testAdd()
    {
        $engines = [];
        $resolver = new StrictEngineResolver();

        foreach (range(0, 10) as $i) {
            $engine = $this->getMockBuilder(EngineInterface::class)->getMock();
            $engine->method('getId')
                ->willReturn("engine1@0.0.$i");
            $engines[] = $engine;
            $resolver->add($engine);
        }

        foreach ($engines as $engine) {
            self::assertEquals($engine, $resolver->get($engine));
        }
    }
}
