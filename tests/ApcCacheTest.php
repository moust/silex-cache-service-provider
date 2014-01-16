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

use Silex\Cache\ApcCache;

class ApcCacheTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped('L\'extension APC n\'est pas disponible.');
        }
    }

    public function instanciateCache()
    {
        $cache = new ApcCache();

        $this->assertInstanceOf('Silex\Cache\ApcCache', $cache);

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