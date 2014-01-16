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

class RedisCacheTest extends \PHPUnit_Framework_TestCase
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
            $this->_redis->flush();
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

    public function testCache()
    {
        $cache = $this->instanciateCache();

        $return = $cache->store('foo', 'bar');
        $this->assertTrue($return);

        $return = $cache->store('bar', array('foo' => 'bar'));
        $this->assertTrue($return);

        $foo = $cache->fetch('foo');
        $this->assertEquals($foo, 'bar');

        $bar = $cache->fetch('bar');
        $this->assertTrue(is_array($bar));
        $this->assertTrue(isset($bar['foo']));
        $this->assertEquals($bar['foo'], 'bar');

        $return = $cache->delete('foo');
        $this->assertTrue($return);

        $foo = $cache->fetch('foo');
        $this->assertFalse($foo);
        
        $bar = $cache->fetch('bar');
        $this->assertTrue(is_array($bar));
        $this->assertTrue(isset($bar['foo']));
        $this->assertEquals($bar['foo'], 'bar');

        $return = $cache->clear();
        $this->assertTrue($return);

        $foo = $cache->fetch('foo');
        $bar = $cache->fetch('bar');

        $this->assertFalse($foo);
        $this->assertFalse($bar);
    }

    public function testCacheTtl()
    {
        $cache = $this->instanciateCache();

        $return = $cache->store('foo', 'bar', 1);
        $this->assertTrue($return);

        $foo = $cache->fetch('foo');
        $this->assertEquals($foo, 'bar');

        sleep(1);

        $foo = $cache->fetch('foo');
        $this->assertFalse($foo);
    }
}