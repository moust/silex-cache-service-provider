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

use Silex\Cache\MemcacheCache;

class MemcacheCacheTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped('L\'extension Memcache n\'est pas disponible.');
        }
    }

    public function instanciateCache()
    {
        $cache = new MemcacheCache();

        $this->assertInstanceOf('Silex\Cache\MemcacheCache', $cache);

        return $cache;
    }

    public function testApcCache()
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

    public function testArrayCacheTtl()
    {
        $cache = $this->instanciateCache();

        $return = $cache->store('foo', 'bar', 2);
        $this->assertTrue($return);

        sleep(1);

        $foo = $cache->fetch('foo');
        $this->assertEquals($foo, 'bar');

        sleep(1);

        $foo = $cache->fetch('foo');
        $this->assertFalse($foo);
    }
}