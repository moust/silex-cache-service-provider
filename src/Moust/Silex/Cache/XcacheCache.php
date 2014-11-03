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

class XcacheCache extends AbstractCache
{
    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return extension_loaded('xcache');
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        if (ini_get('xcache.admin.enable_auth')) {
            throw new \BadMethodCallException('To use all features of \Moust\Silex\Cache\XcacheCache, you must set "xcache.admin.enable_auth" to "Off" in your php.ini.');
        }

        return xcache_clear_cache(XC_TYPE_VAR, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return xcache_isset($key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return $this->exists($key) ? unserialize(xcache_get($key)) : false;
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        return xcache_set($key, serialize($var), (int) $ttl);
    }
}
