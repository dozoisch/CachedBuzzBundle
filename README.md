CachedBuzzBundle
================

Installation
------------

1.  Add this to the `composer.json`:

        {
            "require": {
                "dozoisch/cachedbuzz": "dev-master"
            }
        }


2.  Enable the bundle in `app/AppKernel.php`:

        public function registerBundles()
        {
            $bundles = array(
                // ...
                new Dozoisch\CachedBuzzBundle\DozoischCachedBuzzBundle(),
            );
        }


Configuration
------------

