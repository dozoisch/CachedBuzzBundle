<?php

/*
 * This file is part of the Cached Buzz Bundle.
 *
 * (C) 2013 Hugo Dozois-Caouette
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dozoisch\CachedBuzzBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DozoischCachedBuzzExtension extends Extension {

    const APCCacheClass = 'Dozoisch\CachedBuzzBundle\Cache\APCCache';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('buzz.xml');
        $loader->load('cache.xml');
        $loader->load('services.xml');

        // setup buzz client settings
        $httpClient = $container->getDefinition('buzz.client');
        $httpClient->addMethodCall('setVerifyPeer', array($config['http_client']['verify_peer']));
        $httpClient->addMethodCall('setTimeout', array($config['http_client']['timeout']));
        $httpClient->addMethodCall('setMaxRedirects', array($config['http_client']['max_redirects']));
        $httpClient->addMethodCall('setIgnoreErrors', array($config['http_client']['ignore_errors']));
        $container->setDefinition('dozoisch.cached_buzz.http_client', $httpClient);

        $cache = $container->getDefinition('dozoisch.cached_buzz.cache');
        if ($config['cache']) {
            $cache->setClass($config['cache']);
            $container->setDefinition('dozoisch.cached_buzz.cache', $cache);
        }
        if ($cache->getClass() === self::APCCacheClass && !(extension_loaded('apc') && ini_get('apc.enabled'))) {
            throw new \Exception("APC is not Installed on the server. You can install it by doing apt-get install php-apc");
        }

        if ($config['validator']) {
            $validator = $container->getDefinition('dozoisch.cached_buzz.validator');
            $validator->setClass($config['validator']);
            $container->setDefinition('dozoisch.cached_buzz.validator');
        }
    }

}
