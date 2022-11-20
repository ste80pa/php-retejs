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

use Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface;

/**
 *
 */
class Engine implements EngineInterface
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
     * @var \Ste80pa\Retejs\Engine\VersionCheckerInterface
     */
    private $compatible;
    /**
     * @var \Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface
     */
    private $workerResolver;

    /**
     * @param $name
     * @param $version
     * @param \Ste80pa\Retejs\Engine\VersionCheckerInterface $compatible
     * @param \Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface $workerResolver
     */
    public function __construct(
        $name,
        $version,
        VersionCheckerInterface $compatible,
        WorkerPoolInterface $workerResolver
    ) {
        $this->name = $name;
        $this->version = $version;
        $this->id = "$name@$version";
        $this->compatible = $compatible;
        $this->workerResolver = $workerResolver;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param \Ste80pa\Retejs\Engine\VersionedInterface $versioned
     * @return bool
     */
    public function supports(VersionedInterface $versioned): bool
    {
        return $this->compatible->isCompatible($this, $versioned);
    }

    /**
     * @return \Ste80pa\Retejs\Engine\Worker\WorkerPoolInterface
     */
    public function getWorkers(): WorkerPoolInterface
    {
        return $this->workerResolver;
    }

}
