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

use Moust\Silex\Cache\FileCache;

class FileCacheTest extends AbstractCacheTest
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

        $this->assertInstanceOf('Moust\Silex\Cache\FileCache', $cache);

        return $cache;
    }
}