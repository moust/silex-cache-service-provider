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

use Moust\Silex\Cache\ApcuCache;

class ApcuCacheTest extends AbstractCacheTest
{
    protected function setUp()
    {
        if (!extension_loaded('apcu')) {
            $this->markTestSkipped('L\'extension APCu n\'est pas disponible.');
        }
    }

    public function instanciateCache()
    {
        $cache = new ApcuCache();

        $this->assertInstanceOf('Moust\Silex\Cache\ApcuCache', $cache);

        return $cache;
    }

    public function testCacheTtl()
    {
        $this->markTestSkipped("APCu will only expunged its cache on the next request");
    }
}
