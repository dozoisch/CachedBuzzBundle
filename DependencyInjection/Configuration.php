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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuartion for the CachedBuzz Bundle
 *
 * @author Hugo Dozois <hugo.dozois@gmail.com>
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dozoisch_cached_buzz');

        $notInteger = function ($val) {
                    return !is_int($val) && !(is_string($val) && ctype_digit($val));
                };

        $notPositive = function ($val) {
                    return $val < 0;
                };

        $rootNode
                ->children()
                ->arrayNode('http_client')
                ->addDefaultsIfNotSet()
                ->children()
                ->scalarNode('timeout')
                ->defaultValue(10)
                ->cannotBeEmpty()
                ->validate()
                ->ifTrue($notInteger)
                ->thenInvalid('Value for option "timeout" must be an integer.')
                ->end()
                ->validate()
                ->ifTrue($notPositive)
                ->thenInvalid('Value for option "timeout" must be greater or equal to zero.')
                ->end()
                ->end()
                ->booleanNode('verify_peer')
                ->defaultTrue()
                ->end()
                ->scalarNode('max_redirects')
                ->defaultValue(5)
                ->cannotBeEmpty()
                ->validate()
                ->ifTrue($notInteger)
                ->thenInvalid('Value for option "max_redirects" must be an integer.')
                ->end()
                ->validate()
                ->ifTrue($notPositive)
                ->thenInvalid('Value for option "max_redirects" must be greater or equal to zero.')
                ->end()
                ->end()
                ->booleanNode('ignore_errors')
                ->defaultTrue()
                ->end()
                ->end()
                ->end()
                ->scalarNode('cache')
                ->defaultValue(null)
                ->end()
                ->scalarNode('validator')
                ->defaultValue(null)
                ->end()
        ;

        return $treeBuilder;
    }

}
