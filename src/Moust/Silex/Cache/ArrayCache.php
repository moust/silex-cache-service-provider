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

class ArrayCache extends AbstractCache
{
    /**
     * @var array $data
     */
    private $_data = array();

    /**
     * {@inheritdoc}
     */
    static function isSupported()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->_data = array();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        unset($this->_data[$key]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        $this->_data[$key] = $var;

        return true;
    }
}