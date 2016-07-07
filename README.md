[![Build Status](https://secure.travis-ci.org/moust/silex-cache-service-provider.png?branch=master)](http://travis-ci.org/moust/silex-cache-service-provider)

# Silex Cache Service Provider

The Silex Cache service provider allows you to use several PHP opcode cache in your Silex application.

## Installation

To enable it, add this dependency to your ``composer.json`` file:

```json
{
    "require": {
        "moust/silex-cache": "~2.0"
    }
}
```

## Parameters

- **cache.options**: Array of cache options.
    - **driver**: The cache driver to use. Can be any of: `apc`, `array`, `file`, `memcache`, `memcached`, `xcache`, `redis`, `wincache`.
    - **cache_dir**: Only relevant for `file` cache driver, specifies the path to the cache directory
    - **memcache**: Only relevant for `memcache` cache driver, provide the Memcache instance to use. If not defined, a default Memcache object will be instanciated. See the Memcache documentation for additional informations : [PHP: Memcache - Manual](http://www.php.net/manual/en/book.memcache.php)
    - **memcached**: Only relevant for `memcached` cache driver, provide the Memcached instance to use. If not defined, a default Memcached object will be instanciated. See the Memcached documentation for additional informations : [PHP: Memcached - Manual](http://www.php.net/manual/en/book.memcached.php)
    - **redis**: Only relevant for `redis` cache driver, provide the Redis instance to use. If not defined, a default Redis object will be instanciated. See the PhpRedis documentation for additional informations : [PhpRedis](https://github.com/nicolasff/phpredis/)


## Registering

```php
$app->register(new Moust\Silex\Provider\CacheServiceProvider(), array(
    'cache.options' => array(
        'driver' => 'apc'
    )
));
```

## Usage

The Cache provider provides a cache service. Here is a usage example:

```php
// stores a variable
$app['cache']->store('foo', 'bar');
// stores a variable with a 1 minute lifetime
$app['cache']->store('foo', 'bar', 60);
// fetch variable
echo $app['cache']->fetch('foo');
// delete variable
$app['cache']->delete('foo');
// clear all cached variables
$app['cache']->clear();
```

## Using multiple caches

The Cache provider can allow access to multiple caches. In order to configure the cache drivers, replace the `cache.options` with `caches.options`. `caches.options` is an array of configurations where keys are cache names and values are options:

```php
$app->register(new Moust\Silex\Provider\CacheServiceProvider(), array(
    'caches.options' => array(
        'apc' => array(
            'driver' => 'apc'
        ),
        'filesystem' => array(
            'driver' => 'file',
            'cache_dir' => './temp'
        ),
        'memory' => array(
            'driver' => 'array'
        ),
        'memcache' => array(
            'driver' => 'memcache',
            'memcache' => function () {
                $memcache = new \Memcache;
                $memcache->connect('localhost', 11211);
                return $memcache;
            }
        )
    )
));
```

The first registered cache is the default and can simply be accessed as you would if there was only one. Given the above configuration, these two lines are equivalent:

```php
$app['cache']->store('foo', 'bar');

$app['caches']['apc']->store('foo', 'bar');
```

# Licence

Copyright (c) 2014 Quentin Aupetit

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
