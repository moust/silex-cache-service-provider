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

use Moust\Silex\Cache\ApcCache;

class ApcCacheTest extends AbstractCacheTest
{
    protected function setUp()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped('L\'extension APC n\'est pas disponible.');
        }
    }

    public function instanciateCache()
    {
        $cache = new ApcCache();

        $this->assertInstanceOf('Moust\Silex\Cache\ApcCache', $cache);

        return $cache;
    }

    public function testCacheTtl()
    {
        $this->markTestSkipped("APC will only expunged its cache on the next request");
    }
}