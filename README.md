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

Running as a Service
--------------------

To run the Cached Buzz Bundle as a service, insert this into your services.yml file:

```yaml
parameters:
  dozoisch.bundle.name: Dozoisch\CachedBuzzBundle

services:
  # The actual service
  dozoisch.buzz.browser:
    class: '%dozoisch.bundle.name%\Browser'
    arguments: ['@dozoisch.buzz.cacher', '@dozoisch.buzz.client.curl']
    
  # Parametring.
  dozoisch.buzz.client.curl:
    class:  'Buzz\Client\Curl'
    public: false
    calls:
      - [setVerifyPeer, [false]] # this is optional
      - [setTimeout, [100]] # this is optional

  dozoisch.buzz.cacheinterface:
    class: '%dozoisch.bundle.name%\Cache\APCCache'

  dozoisch.buzz.cachevalidator:
    class: '%dozoisch.bundle.name%\Cache\CacheValidator'

  dozoisch.buzz.cacher:
    class: '%dozoisch.bundle.name%\Cacher'
    arguments: ['@dozoisch.buzz.cacheinterface', '@dozoisch.buzz.cachevalidator']

```

*When using it like that it overrides some of the normal bundles setting and thus, the cacher and client parameters for the browser are no longer optional.*

How to use it
--------------------

You can now call the browser just as you would with any other service.

###Container aware class

If you wish to call it from a container aware class, a controller for example, just do `$this->get('dozoisch.buzz.browser');`.

###Non-container aware class

To call it from a service which is not container aware, first add this to your services.yml

```yaml

services:
  my.super.service:
    class: xclass.class
    arguments: ['@dozoisch.buzz.browser']
```

And make sure to have the appropriate constructor in your class :

```php
/** @var Buzz\Browser */
protected $browser;

public function __construct(\Buzz\Browser $browser) {
    $this->browser = $browser;
}

```

###Actually using it

After retrieving the browser, you can use it as you would with the normal buzz browser. This bundles is meant to be used seamlessly over the normal buzz instance.

```php
$response = $this->browser->get("http://example.com");
$content = $response->getContent();
```
The available functions are post, head, patch, put, delete.

[buzzlnk]:https://github.com/kriswallsmith/Buzz
[apclnk]:http://www.php.net/manual/en/book.apc.php
[mitlnk]:http://en.wikipedia.org/wiki/MIT_License
[liclnk]:./LICENSE
