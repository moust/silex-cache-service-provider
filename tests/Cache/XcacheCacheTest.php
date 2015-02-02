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

use Moust\Silex\Cache\XcacheCache;

class XcacheCacheTest extends AbstractCacheTest
{
    protected function setUp()
    {
        if (!extension_loaded('xcache')) {
            $this->markTestSkipped('L\'extension XCache n\'est pas disponible.');
        }
    }

    public function instanciateCache()
    {
        $cache = new XcacheCache();

        $this->assertInstanceOf('Moust\Silex\Cache\XcacheCache', $cache);

        return $cache;
    }
}