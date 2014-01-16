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

use Moust\Silex\Cache\CacheFactory;

class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{
	private $_drivers = array(
        'apc'      => '\\Moust\\Silex\\Cache\\ApcCache',
        'array'    => '\\Moust\\Silex\\Cache\\ArrayCache',
        'file'     => '\\Moust\\Silex\\Cache\\FileCache',
        'memcache' => '\\Moust\\Silex\\Cache\\MemcacheCache',
    );

    private $_options = array();

	public function testInstanciate()
	{
		$factory = new CacheFactory($this->_drivers, $this->_options);
		return $factory;
	}

	/**
     * @depends testInstanciate
     */
	public function testGetApcCache(CacheFactory $factory)
	{
		if (!extension_loaded('apc')) {
            $this->markTestSkipped('L\'extension APC n\'est pas disponible.');
        }

		$cache = $factory->getCache('apc');

		$this->assertInstanceOf('Moust\Silex\Cache\ApcCache', $cache);
	}

	/**
     * @depends testInstanciate
     */
	public function testGetArrayCache(CacheFactory $factory)
	{
		$cache = $factory->getCache('array');

		$this->assertInstanceOf('Moust\Silex\Cache\ArrayCache', $cache);
	}

	/**
     * @depends testInstanciate
     */
	public function testGetFileCache(CacheFactory $factory)
	{
		$cache = $factory->getCache('file', array(
			'cache_dir' => './temp'
		));

		$this->assertInstanceOf('Moust\Silex\Cache\FileCache', $cache);
	}

	/**
     * @depends testInstanciate
     */
	public function testGetMemcacheCache(CacheFactory $factory)
	{
		if (!extension_loaded('memcache')) {
            $this->markTestSkipped('L\'extension Memcache n\'est pas disponible.');
        }

		$cache = $factory->getCache('memcache');

		$this->assertInstanceOf('Moust\Silex\Cache\MemcacheCache', $cache);

		$cache = $factory->getCache('memcache', array(
			'memcache' => function () {
                $memcache = new \Memcache;
                $memcache->connect('localhost', 11211);
                return $memcache;
            }
		));

		$this->assertInstanceOf('Moust\Silex\Cache\MemcacheCache', $cache);
	}
}