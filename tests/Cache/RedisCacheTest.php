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

use Moust\Silex\Cache\RedisCache;
use Redis;

class RedisCacheTest extends AbstractCacheTest
{
    private $_redis;

    protected function setUp()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('L\'extension Redis n\'est pas disponible.');
        }

        $this->_redis = new Redis();

        if (@$this->_redis->connect('127.0.0.1') === false) {
            unset($this->_redis);
            $this->markTestSkipped('The Redis cannot connect to redis');
        }
    }

    public function tearDown()
    {
        if ($this->_redis instanceof Redis) {
            $this->_redis->flushDB();
        }
    }

    public function instanciateCache()
    {
        $cache = new RedisCache(array(
            'redis' => $this->_redis
        ));

        $this->assertInstanceOf('Moust\Silex\Cache\RedisCache', $cache);

        return $cache;
    }
}