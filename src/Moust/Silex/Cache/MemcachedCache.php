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

use Memcached;

class MemcachedCache extends AbstractCache
{
    /**
     * @var Memcached
     */
    private $_memcached;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = array())
    {
        if (isset($options['memcached']) && is_callable($options['memcached'])) {
            $options['memcached'] = $options['memcached']();
        }

        if (!isset($options['memcached']) || !$options['memcached'] instanceof Memcached) {
            $options['memcached'] = new Memcached(uniqid());
            $options['memcached']->setOption(Memcached::OPT_COMPRESSION, false);
            $options['memcached']->addServer('127.0.0.1', 11211);
        }

        $this->setMemcached($options['memcached']);
    }

    /**
     * Sets the Memcached instance to use.
     *
     * @param Memcached $memcached
     */
    public function setMemcached(Memcached $memcached)
    {
        $this->_memcached = $memcached;
    }

    /**
     * Gets the Memcached instance used by the cache.
     *
     * @return Memcached
     */
    public function getMemcached()
    {
        return $this->_memcached;
    }

    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return extension_loaded('Memcached');
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->_memcached->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return $this->_memcached->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return !!$this->_memcached->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return $this->_memcached->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        return $this->_memcached->set($key, $var, (int) $ttl);
    }
}
