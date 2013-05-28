<?php

/*
 * This file is part of the Cached Buzz Bundle.
 *
 * (C) 2013 Hugo Dozois-Caouette
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dozoisch\CachedBuzzBundle;

use Buzz\Message\Request;
use Buzz\Message\Response;
use Dozoisch\CachedBuzzBundle\Cache\CacheInterface;
use Dozoisch\CachedBuzzBundle\Cache\CacheValidatorInterface;

/**
 * Cacher is the class that validates if an entry is cached or can be cached, and then caches it.
 * The two classes it uses can be changed as long as they implement the correct interfaces
 *
 * @author hugo
 */
class Cacher {

    /**
     *
     * @var CacheInterface
     */
    private $cache;

    /**
     *
     * @var CacheValidatorInterface
     */
    private $validator;

    function __construct(CacheInterface $cache, CacheValidatorInterface $validator) {
        $this->cache = $cache;
        $this->validator = $validator;
    }

    /**
     * 
     * @param \Buzz\Message\Request $request
     * @return array|false array(request,response) if it exists, false if the request is not cached
     */
    public function retrieveCachedResponse(Request $request) {
        if ($this->validator->isRequestCacheable($request)) {
            $key = $this->buildKey($request);
            $data = $this->cache->get($key);
            if ($data && !$this->validator->isExpired($data['response'])) {
                return $data;
            } else {
                $this->cache->delete($key);
            }
        }
        return false;
    }

    /**
     * 
     * @param \Buzz\Message\RequestInterface $request
     * @param \Buzz\Message\Reponse $response
     */
    public function cacheResponse(Request $request, Response $response) {
        if ($this->validator->isCacheable($request, $response)) {
            $key = $this->buildKey($request);
            $this->cache->set($key, array('request' => $request, 'response' => $response));
        }
    }

    /**
     * 
     * @param \Buzz\Message\Request $request
     * @return string the key specific to the request
     */
    protected function buildKey(Request $request) {
        return $request->getUrl() . $request->getMethod() . implode($request->getHeaders());
    }

}

?>
