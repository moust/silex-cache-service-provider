<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Cache;

interface CacheInterface
{
    /**
     * Invalidate all items in the cache
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     **/
    public function clear();

    /**
     * Delete an item
     *
     * @param mixed $key The key of the item to delete.
     * @return bool Returns TRUE on success or FALSE on failure.
     **/
    public function delete($key);

    /**
     * Checks if APC key exists
     *
     * @param mixed $key The key of the item to retrieve.
     * @return bool Returns TRUE if the key exists, otherwise FALSE.
     **/
    public function exists($key);

    /**
     * Fetch a stored variable from the cache
     *
     * @param mixed $key The key used to store the value
     * @return mixed The stored variable
     **/
    public function fetch($key);

    /**
     * Store variable in the cache
     *
     * @param mixed $key The key to use to store the value
     * @param mixed $var The variable to store
     * @param int $ttl The expiration time, defaults to 0.
     **/
    public function store($key, $var = null, $ttl = 0);

    /**
     * Check if the cache driver is supported
     *
     * @return bool Returns TRUE if supported or FALSE if not.
     **/
    static function isSupported();
}
