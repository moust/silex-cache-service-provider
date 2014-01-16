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

use Silex\Cache\FileCache;

class FileCacheTest extends \PHPUnit_Framework_TestCase
{
    public static $cacheDir = './temp';

    public static function setUpBeforeClass()
    {
        self::removeCacheDir();
    }

    public static function tearDownAfterClass()
    {
        self::removeCacheDir();
    }

    public static function removeCacheDir()
    {
        // remove cache directory if exists
        if (file_exists(self::$cacheDir)) {
            foreach (glob(self::$cacheDir . "/*") as $filename) {
                unlink($filename);
            }
            rmdir(self::$cacheDir);
        }
    }

    public function instanciateCache()
    {
        $cache = new FileCache(array('cache_dir' => self::$cacheDir));

        $this->assertInstanceOf('Silex\Cache\FileCache', $cache);

        return $cache;
    }

    public function testFileCache()
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

    public function testFileCacheTtl()
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