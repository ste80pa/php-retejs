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
interface EngineResolverInterface
{
    /**
     * @param \Ste80pa\Retejs\Engine\VersionedInterface $versioned
     * @return bool
     */
    public function has(VersionedInterface $versioned): bool;

    /**
     * @param \Ste80pa\Retejs\Engine\VersionedInterface $versioned
     * @return \Ste80pa\Retejs\Engine\EngineInterface
     * @throws \Ste80pa\Retejs\Exception\EngineNotFoundException
     */
    public function get(VersionedInterface $versioned): EngineInterface;

    /**
     * @param \Ste80pa\Retejs\Engine\EngineInterface $engine
     * @return \Ste80pa\Retejs\Engine\EngineResolverInterface
     */
    public function add(EngineInterface $engine): EngineResolverInterface;

    /**
     * @return  array
     */
    public function getAll(): array;
}
