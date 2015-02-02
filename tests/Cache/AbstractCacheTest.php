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

abstract class AbstractCacheTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Creates an AbstractCache instance and ensures its valid
     *
     * @return AbstractCache
     **/
    abstract public function instanciateCache();


    /**
     * Tests simple variable cache
     **/
    public function testSimpleCache()
    {
        $cache = $this->instanciateCache();

        $this->assertTrue( $cache->store('foo', 'bar') );

        $this->assertEquals( $cache->fetch('foo'), 'bar');

        $this->assertTrue( $cache->delete('foo') );
    }


    /**
     * Tests complex variable cache
     **/
    public function testComplexCache()
    {
        $cache = $this->instanciateCache();

        $this->assertTrue( $cache->store('array', array('foo' => 'bar')) );

        $array = $cache->fetch('array');
        $this->assertTrue(is_array($array));
        $this->assertTrue(isset($array['foo']));
        $this->assertEquals($array['foo'], 'bar');
    }


    /**
     * Tests the existence of a stored variable
     **/
    public function testCacheExists()
    {
        $cache = $this->instanciateCache();

        $cache->delete('foo');

        $this->assertFalse( $cache->exists('foo') );

        $cache->store('foo', 'bar');

        $this->assertTrue( $cache->exists('foo') );
    }


    /**
     * Tests clear cache
     **/
    public function testClearCache()
    {
        $cache = $this->instanciateCache();

        $cache->store('foo', 'bar');
        
        $cache->store('bar', array('foo' => 'bar'));

        $this->assertTrue( $cache->clear() );

        $this->assertFalse( $cache->exists('foo') );
        $this->assertFalse( $cache->exists('bar') );
    }

    /**
     * Tests variable time to live if supported
     **/
    public function testCacheTtl()
    {
        $cache = $this->instanciateCache();

        $this->assertTrue( $cache->store('foo', 'bar', 1) );

        $this->assertEquals( $cache->fetch('foo'), 'bar');

        sleep(2);

        $this->assertFalse( $cache->fetch('foo') );
    }
}