<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex\Tests;

use Silex\Application;
use Silex\Provider\CacheServiceProvider;

class CacheServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    protected function createApplication()
    {
        if (!extension_loaded('apc')) {
            $this->setExpectedException('Silex\Cache\CacheException');
        }

        if (!extension_loaded('memcache')) {
            $this->setExpectedException('Silex\Cache\CacheException');
        }

        $app = new Application();

        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'default' => array(
                    'driver'    => 'array',
                ),
                'apc'    => array(
                    'driver'    => 'apc',
                ),
                'array'  => array(
                    'driver'    => 'array',
                ),
                'file'  => array(
                    'driver'    => 'file',
                    'cache_dir' => __DIR__ . DIRECTORY_SEPARATOR . 'temp',
                ),
                'memcache' => array(
                    'driver'    => 'memache',
                    'memcache'  => function () {
                        $memcache = new \Memcache();
                        $memcache->connect('localhost', 11211);
                        return $memcache;
                    }
                )
            )
        ));

        return $app;
    }

    public function testRegister()
    {
        $app = $this->createApplication();

        // default cache
        $this->assertInstanceOf('Silex\Cache\AbstractCache', $app['cache']);

        // ApcCache
        if (extension_loaded('apc')) {
            $this->assertInstanceOf('Silex\Cache\ApcCache', $app['caches']['apc']);
        }

        // ArrayCache
        $this->assertInstanceOf('Silex\Cache\ArrayCache', $app['caches']['array']);

        // FileCache
        $this->assertInstanceOf('Silex\Cache\FileCache', $app['caches']['file']);

        // MemcacheCache
        if (extension_loaded('memcache')) {
            $this->assertInstanceOf('Silex\Cache\MemcacheCache', $app['caches']['memcache']);
        }
    }

}