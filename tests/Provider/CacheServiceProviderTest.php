<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Tests\Provider;

use Silex\Application;
use Moust\Silex\Provider\CacheServiceProvider;
use Memcache;

class CacheServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionsInitializer()
    {
        $app = new Application();
        $app->register(new CacheServiceProvider());

        $this->assertInstanceof('Moust\Silex\Cache\AbstractCache', $app['cache']);
    }

    public function testSingleCache()
    {
        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'array'
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\ArrayCache', $app['cache']);
    }

    public function testMultipleCache()
    {
        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'caches.options' => array(
                'memory' => array(
                    'driver' => 'array'
                ),
                'filesystem' => array(
                    'driver' => 'file',
                    'cache_dir' => './temp'
                ),
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\ArrayCache', $app['caches']['memory']);

        $this->assertInstanceof('Moust\Silex\Cache\FileCache', $app['caches']['filesystem']);
        $this->assertEquals('./temp', $app['caches']['filesystem']->getCacheDir());

        // check default cache
        $this->assertEquals($app['cache'], $app['caches']['memory']);
    }

    public function testApcProvider()
    {
        if (!extension_loaded('apc')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'apc',
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\ApcCache', $app['cache']);
    }

    public function testMemcacheProvider()
    {
        if (!extension_loaded('memcache')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'memcache',
                'memcache' => function () {
                    $memcache = new Memcache;
                    $memcache->connect('localhost', 11211);
                    return $memcache;
                }
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\MemcacheCache', $app['cache']);
        $this->assertInstanceof('Memcache', $app['cache']->getMemcache());
    }

    public function testMemcachedProvider()
    {
        if (!extension_loaded('Memcached')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'memcached',
                'memcached' => function () {
                    $memcached = new \Memcached(uniqid());
                    $memcached->setOption(\Memcached::OPT_COMPRESSION, false);
                    $memcached->addServer('127.0.0.1', 11211);
                    return $memcached;
                }
            ),
        ));

        $this->assertInstanceof('\Moust\Silex\Cache\MemcachedCache', $app['cache']);
        $this->assertInstanceof('\Memcached', $app['cache']->getMemcached());
    }

    public function testRedisProvider()
    {
        if (!extension_loaded('redis')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'redis',
                'redis' => function () {
                    $redis = new \Redis();
                    $redis->connect('127.0.0.1');
                    return $redis;
                }
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\RedisCache', $app['cache']);
        $this->assertInstanceof('Redis', $app['cache']->getRedis());
    }

}