# Silex Cache Service Provider

The Silex Cache service provider allows you to use several PHP opcode cache in your Silex application.


To enable it, add this dependency to your ``composer.json`` file:

.. code-block:: json

    {
        "require": {
            "moust/silex-cache-service-provider": "*"
        }
    }

And enable it in your application:

.. code-block:: php

    use Silex\Provider\CacheServiceProvider;

    $app->register(new CacheServiceProvider(), array(
        'cache.options' => array(
            // default driver
            'default'  => array(
                'driver'    => 'apc',
            ),
            // filesystem based cache
            'global'   => array(
                'driver'    => 'file',
                'cache_dir' => __DIR__ . DIRECTORY_SEPARATOR . 'temp',
            ),
            // Memcache based cache
            'memcache' => array(
                'driver'    => 'memache',
                'memcache'  => function () {
                    $memcache = new \Memcache();
                    $memcache->connect('localhost', 11211);
                    return $memcache;
                }
            )
        )
    ));

Now you can using it like that:

.. code-block:: php
    
    // store variable in default cache driver
    $app['cache']->store('foo', 'bar');
    // fetch variable
    echo $app['cache']->fetch('foo');
    // delete variable
    $app['cache']->delete('foo');
    // clear all cached variables
    $app['cache']->clear();

    // or use an other defined cache driver

    // store variable in default cache driver
    $app['caches']['memcache']->store('foo', 'bar');
    // fetch variable
    $app['caches']['memcache']->fetch('foo');
    // delete variable
    $app['caches']['memcache']->delete('foo');
    // clear all cached variables
    $app['caches']['memcache']->clear();


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