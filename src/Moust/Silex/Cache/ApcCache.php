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

class ApcCache extends AbstractCache
{
    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return extension_loaded('apc');
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return apc_clear_cache();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return apc_fetch($key);
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        return apc_store($key, $var, (int) $ttl);
    }
}
