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
    static function supportTtl()
    {
        return false;
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
        if ( isset($this->_data[$key]) && $this->isContentAlive($this->_data[$key]) ) {
            return $this->_data[$key]['data'];
        }
        else {
            $this->delete($key);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function store($key, $var = null, $ttl = 0)
    {
        $this->_data[$key] = array('data' => $var, 'ttl' => (int) $ttl, 'created_at' => time());

        return true;
    }

    protected function isContentAlive($content)
    {
        return ($content['ttl'] === 0) || ((time() - $content['created_at']) < $content['ttl']);
    }
}
