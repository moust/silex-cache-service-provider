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

use Moust\Silex\Cache\MemcacheCache;
use Memcache;

class MemcacheCacheTest extends AbstractCacheTest
{
    private $_memcache;

    protected function setUp()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped('L\'extension Memcache n\'est pas disponible.');
        }

        $this->_memcache = new Memcache();

        if (@$this->_memcache->connect('localhost', 11211) === false) {
            unset($this->_memcache);
            $this->markTestSkipped('The Memcache cannot connect to memcache');
        }
    }

    public function tearDown()
    {
        if ($this->_memcache instanceof Memcache) {
            $this->_memcache->flush();
        }
    }

    public function instanciateCache()
    {
        $cache = new MemcacheCache(array(
            'memcache' => $this->_memcache
        ));

        $this->assertInstanceOf('Moust\Silex\Cache\MemcacheCache', $cache);

        return $cache;
    }
}