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

        $cache = $app['cache'];
        $this->assertInstanceof('Moust\Silex\Cache\ArrayCache', $cache);
    }

    public function testMultipleCache()
    {
        if (!extension_loaded('apc')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        if (!extension_loaded('memcache')) {
            $this->setExpectedException('Moust\Silex\Cache\CacheException');
        }

        $app = new Application();
        $app->register(new CacheServiceProvider(), array(
            'caches.options' => array(
                'memory' => array(
                    'driver' => 'array'
                ),
                'apc' => array(
                    'driver' => 'apc'
                ),
                'filesystem' => array(
                    'driver' => 'file', 
                    'cache_dir' => './temp'
                ),
                'memcache' => array(
                    'driver' => 'memcache',
                    'memcache' => function () {
                        $memcache = new Memcache;
                        $memcache->connect('localhost', 11211);
                        return $memcache;
                    }
                )
            ),
        ));

        $this->assertInstanceof('Moust\Silex\Cache\ArrayCache', $app['caches']['memory']);

        // check default cache
        $this->assertSame($app['cache'], $app['caches']['memory']);

        $this->assertInstanceof('Moust\Silex\Cache\FileCache', $app['caches']['filesystem']);
        $this->assertEquals('./temp', $app['caches']['filesystem']->getCacheDir());

        $this->assertInstanceof('Moust\Silex\Cache\ApcCache', $app['caches']['apc']);

        $this->assertInstanceof('Moust\Silex\Cache\MemcacheCache', $app['caches']['memcache']);
        $this->assertInstanceof('Memcache', $app['caches']['memcache']->getMemcache());
    }

}