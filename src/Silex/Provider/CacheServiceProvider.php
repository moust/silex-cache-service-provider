<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Silex\Cache\CacheFactory;

class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['cache.drivers'] = function () {
            return array(
                'apc'      => '\\Silex\\Cache\\ApcCache',
                'array'    => '\\Silex\\Cache\\ArrayCache',
                'file'     => '\\Silex\\Cache\\FileCache',
                'memcache' => '\\Silex\\Cache\\MemcacheCache',
            );
        };

        $app['cache.factory'] = function ($app) {
            return new CacheFactory($app['cache.drivers'], $app['cache.options']);
        };

        $app['cache'] = $app->share(function ($app) {

            $app['cache.options'] = array_replace(
                array(
                    'default' => array('driver' => 'array')
                ),
                $app['cache.options']
            );

            $default = $app['cache.options']['default'];

            return $app['cache.factory']->getCache($default['driver'], $default);
        });

        $app['caches'] = $app->share(function($app) {
            $caches = new \Pimple;

            foreach ($app['cache.options'] as $cache => $options) {
                $caches[$cache] = $app['cache.factory']->getCache($options['driver'], $options);
            }

            return $caches;
        });
    }

    public function boot(Application $app)
    {

    }
}