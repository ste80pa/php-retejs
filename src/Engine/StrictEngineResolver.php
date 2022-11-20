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

use Ste80pa\Retejs\Exception\EngineNotFoundException;

/**
 *
 */
class StrictEngineResolver implements EngineResolverInterface
{
    /**
     * @var array
     */
    private $engines = [];

    /**
     * @param \Ste80pa\Retejs\Engine\VersionedInterface $versioned
     * @return \Ste80pa\Retejs\Engine\EngineInterface
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function get(VersionedInterface $versioned): EngineInterface
    {
        if ($this->has($versioned)) {
            return $this->engines[$versioned->getId()];
        }

        throw new EngineNotFoundException('Engine not found');
    }

    /**
     * @param \Ste80pa\Retejs\Engine\VersionedInterface $versioned
     * @return bool
     */
    public function has(VersionedInterface $versioned): bool
    {
        return isset($this->engines[$versioned->getId()]);
    }

    /**
     * @param \Ste80pa\Retejs\Engine\EngineInterface $engine
     * @return EngineResolverInterface
     */
    public function add(EngineInterface $engine): EngineResolverInterface
    {
        $this->engines[$engine->getId()] = $engine;
        return $this;
    }

    /**
     * @return \Ste80pa\Retejs\Engine\EngineInterface[]
     */
    public function getAll(): array
    {
        return $this->engines ?? [];
    }
}
