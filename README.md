CachedBuzzBundle
================

This bundle is meant to provide a way to cache request made with the [buzz bundle][buzzlnk]. This bundle can be used exactly like [buzz][buzzlnk], the cache is integrated directly. The bundle is under the [MIT license][mitlnk]. For more information see the file called [LICENSE][liclnk] in the root of the path.

Installation
------------

1.  Add this to the `composer.json`:

    ```json
    {
        "require": {
            "dozoisch/cachedbuzz": "dev-master"
        }
    }
    ```

2.  Enable the bundle in `app/AppKernel.php`:

    ```php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Dozoisch\CachedBuzzBundle\DozoischCachedBuzzBundle(),
        );
    }
    ```

Configuration
------------

**No setting is mandatory** as every thing fallback to a default setting or a default implementation provided by the bundle. The default `cache` uses [APC][apclnk]. If the module is not available on your web server, the module initialization will fail.


Here is the full configuration *(in yaml)* possible, with the defaults value :

```yaml
dozoisch_cached_buzz:
    http_client:
        timeout: 10
        verify_peer: true
        max_redirects: 5
        ignore_errors: true
    cache: null #takes a string
    validator: null #takes a string
```

In the `http_client` is used to configure the buzz client. The `cache` setting takes a string that should be a class or a service. Same thing for the `validator`.

The `cache` has to implement the class `Dozoisch\CachedBuzzBundle\Cache\CacheInterface`.  
The `validator` has to implement the class `Dozoisch\CachedBuzzBundle\Cache\CacheValidatorInterface`.


[buzzlnk]:https://github.com/kriswallsmith/Buzz
[apclnk]:http://www.php.net/manual/en/book.apc.php
[mitlnk]:http://en.wikipedia.org/wiki/MIT_License
[liclnk]:./LICENSE
