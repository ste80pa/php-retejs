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

namespace Ste80pa\Retejs\Test\Unit\Engine\Worker;

use PHPUnit\Framework\TestCase;
use Ste80pa\Retejs\Engine\Worker\WorkerInterface;
use Ste80pa\Retejs\Engine\Worker\WorkerPool;
use Ste80pa\Retejs\Exception\MissingWorkerException;

/**
 *
 */
class WorkerPoolTest extends TestCase
{


    /**
     * @covers  \Ste80pa\Retejs\Engine\Worker\WorkerPool::get
     * @covers  \Ste80pa\Retejs\Engine\Worker\WorkerPool::has
     *
     * @return void
     * @throws \Ste80pa\Retejs\Exception\MissingWorkerException
     */
    public function testMissingWorkerEmptyPool()
    {
        $worker = new WorkerPool();

        static::assertFalse($worker->has('worker'));
        $this->expectException(MissingWorkerException::class);
        $worker->get('worker');
    }

    /**
     * @covers  \Ste80pa\Retejs\Engine\Worker\WorkerPool::get
     * @covers  \Ste80pa\Retejs\Engine\Worker\WorkerPool::add
     * @covers  \Ste80pa\Retejs\Engine\Worker\WorkerPool::has
     * @return void
     * @throws \Ste80pa\Retejs\Exception\MissingWorkerException
     */
    public function testMissingWorker()
    {
        $workerPool = new WorkerPool();

        $worker = $this->getMockBuilder(WorkerInterface::class)->getMock();
        static::assertFalse($workerPool->has('worker'));

        $workerPool->add('worker', $worker);
        static::assertTrue($workerPool->has('worker'));
        static::assertEquals($worker, $workerPool->get('worker'));

        $this->expectException(MissingWorkerException::class);
        $workerPool->get('non_existing_worker');
    }
}
