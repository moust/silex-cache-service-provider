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

use Redis;

class RedisCache extends AbstractCache
{
    /**
     * @var Redis
     */
    private $_redis;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = array())
    {
        if (isset($options['redis']) && is_callable($options['redis'])) {
            $options['redis'] = $options['redis']();
        }

        if (!isset($options['redis']) || !$options['redis'] instanceof Redis) {
            $options['redis'] = new Redis;
            $options['redis']->connect('127.0.0.1');
        }

        $this->setRedis($options['redis']);
    }

    /**
     * Sets the Redis instance to use.
     *
     * @param Redis $redis
     */
    public function setRedis(\Redis $redis)
    {
        $redis->setOption(Redis::OPT_SERIALIZER, $this->getSerializerValue());
        $this->_redis = $redis;
    }

    /**
     * Gets the Redis instance used by the cache.
     *
     * @return Redis
     */
    public function getRedis()
    {
        return $this->_redis;
    }

    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return extension_loaded('redis');
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->_redis->flushDB();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return !!$this->_redis->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return !!$this->_redis->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return $this->_redis->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        if ($ttl > 0) {
            return $this->_redis->setex($key, (int) $ttl, $var);
        }

        return $this->_redis->set($key, $var);
    }

    /**
     * Returns the serializer constant to use. If Redis is compiled with
     * igbinary support, that is used. Otherwise the default PHP serializer is
     * used.
     *
     * @return integer One of the Redis::SERIALIZER_* constants
     */
    protected function getSerializerValue()
    {
        return defined('Redis::SERIALIZER_IGBINARY') ? Redis::SERIALIZER_IGBINARY : Redis::SERIALIZER_PHP;
    }
}
