<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Tests\Cache;

use Moust\Silex\Cache\ArrayCache;

class ArrayCacheTest extends AbstractCacheTest
{
    public function instanciateCache()
    {
        $cache = new ArrayCache();

        $this->assertInstanceOf('Moust\Silex\Cache\ArrayCache', $cache);

        return $cache;
    }
}