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

class ApcuCache extends AbstractCache
{
    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return extension_loaded('apcu');
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return apcu_clear_cache();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return apcu_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return apcu_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return apcu_fetch($key);
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        return apcu_store($key, $var, (int) $ttl);
    }
}
