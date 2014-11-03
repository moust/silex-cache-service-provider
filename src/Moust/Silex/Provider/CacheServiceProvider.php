<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Moust\Silex\Cache\CacheFactory;

class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['cache.default_options'] = array(
            'driver' => 'array'
        );

        $app['cache.drivers'] = function () {
            return array(
                'apc'       => '\\Moust\\Silex\\Cache\\ApcCache',
                'array'     => '\\Moust\\Silex\\Cache\\ArrayCache',
                'file'      => '\\Moust\\Silex\\Cache\\FileCache',
                'memcache'  => '\\Moust\\Silex\\Cache\\MemcacheCache',
                'memcached' => '\\Moust\\Silex\\Cache\\MemcachedCache',
                'xcache'    => '\\Moust\\Silex\\Cache\\XcacheCache',
                'redis'     => '\\Moust\\Silex\\Cache\\RedisCache',
                'wincache'  => '\\Moust\\Silex\\Cache\\WincacheCache',
            );
        };

        $app['cache.factory'] = $app->share(function ($app) {
            return new CacheFactory($app['cache.drivers'], $app['caches.options']);
        });

        $app['caches.options.initializer'] = $app->protect(function () use ($app) {
            static $initialized = false;

            if ($initialized) {
                return;
            }

            $initialized = true;

            if (!isset($app['caches.options'])) {
                $app['caches.options'] = array('default' => isset($app['cache.options']) ? $app['cache.options'] : array());
            }

            $tmp = $app['caches.options'];
            foreach ($tmp as $name => &$options) {
                $options = array_replace($app['cache.default_options'], $options);

                if (!isset($app['caches.default'])) {
                    $app['caches.default'] = $name;
                }
            }
            $app['caches.options'] = $tmp;
        });

        $app['caches'] = $app->share(function ($app) {
            $app['caches.options.initializer']();

            $caches = new \Pimple();
            foreach ($app['caches.options'] as $name => $options) {
                if ($app['caches.default'] === $name) {
                    // we use shortcuts here in case the default has been overridden
                    $config = $app['cache.config'];
                } else {
                    $config = $app['caches.config'][$name];
                }

                $caches[$name] = $caches->share(function ($caches) use ($app, $config) {
                    return $app['cache.factory']->getCache($config['driver'], $config);
                });
            }

            return $caches;
        });

        $app['caches.config'] = $app->share(function ($app) {
            $app['caches.options.initializer']();

            $configs = new \Pimple();
            foreach ($app['caches.options'] as $name => $options) {
                $configs[$name] = $options;
            }

            return $configs;
        });

        // shortcuts for the "first" cache
        $app['cache'] = $app->share(function ($app) {
            $caches = $app['caches'];

            return $caches[$app['caches.default']];
        });

        $app['cache.config'] = $app->share(function ($app) {
            $caches = $app['caches.config'];

            return $caches[$app['caches.default']];
        });
    }

    public function boot(Application $app)
    {

    }
}
