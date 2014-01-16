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

class MemcachedCacheTest extends \PHPUnit_Framework_TestCase
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