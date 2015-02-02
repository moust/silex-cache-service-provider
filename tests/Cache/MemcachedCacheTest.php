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

use Moust\Silex\Cache\MemcachedCache;
use Memcached;

class MemcachedCacheTest extends AbstractCacheTest
{
    private $_memcached;

    protected function setUp()
    {
        if (!extension_loaded('memcached')) {
            $this->markTestSkipped('L\'extension Memcached n\'est pas disponible.');
        }

        $this->_memcached = new Memcached();
        $this->_memcached->setOption(Memcached::OPT_COMPRESSION, false);
        $this->_memcached->addServer('127.0.0.1', 11211);

        if (@fsockopen('127.0.0.1', 11211) === false) {
            unset($this->memcached);
            $this->markTestSkipped('The Memcached cannot connect to memcached');
        }
    }

    public function tearDown()
    {
        if ($this->_memcached instanceof Memcached) {
            $this->_memcached->flush();
        }
    }

    public function instanciateCache()
    {
        $cache = new MemcachedCache(array(
            'memcached' => $this->_memcached
        ));

        $this->assertInstanceOf('Moust\Silex\Cache\MemcachedCache', $cache);

        return $cache;
    }
}