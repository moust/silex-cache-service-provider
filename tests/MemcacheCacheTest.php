<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Tests;

use Moust\Silex\Cache\MemcacheCache;
use Memcache;

class MemcacheCacheTest extends \PHPUnit_Framework_TestCase
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