<?php

/*
 * This file is part of the Cached Buzz Bundle.
 *
 * (C) 2013 Hugo Dozois-Caouette
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dozoisch\CachedBuzzBundle\Cache;

use Buzz\Message\Response;
use Buzz\Message\Request;

/**
 * This valids if a buzz response is cacheable or not.
 *
 * @author hugo
 */
class CacheValidator implements CacheValidatorInterface {

    protected static $CACHEABLE_HTTP_METHODS = array('GET', 'HEAD');
    protected static $CACHEABLE_STATUS_CODES = array('200', '203', '204', '205', '300', '301', '410');

    /**
     * @todo finish it
     * @param \Buzz\Message\Request $request
     * @param \Buzz\Message\Response $response
     * @return boolean
     */
    public function isCacheable(Request $request, Response $response) {
        if ($this->isRequestCacheable($request) && $this->isResponseCacheable($response)) {
            return true;
        }

        //add more
        return false;
    }

    /**
     * 
     * @param \Buzz\Message\Response $response
     * @return boolean
     */
    public function isExpired(Response $response, $minFresh = 5) {
        $expires = $response->getHeader('expires');
        if ($expires !== null && time() < ($expires + $minFresh)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param \Buzz\Message\Request $request
     * @return boolean
     */
    public function isRequestCacheable(Request $request) {
        if ($this->isHTTPMethodCacheable($request->getMethod())) {
            return false;
        }

        //[rfc2616-14.8]
        if ($request->getHeader("authorization")) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param \Buzz\Message\Response $response
     * @return boolean
     */
    public function isResponseCacheable(Response $response) {
        if ($this->isExpired($response)) {
            return false;
        }
        if (!$this->isStatusCodeCacheable($response->getStatusCode())) {
            return false;
        }

        if ($response->getHeader('etag')) {
            return false;
        }
        if ($response->getHeader('vary')) {
            return false;
        }
        if (!$this->isCacheControlCacheable($response->getHeader('cache-control'))) {
            return false;
        }
        $pragma = $response->getHeader('pragma');
        if ($pragma == 'no-cache' || strpos($pragma, 'no-cache') !== false) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @param string $statusCode
     * @return boolean
     */
    private function isStatusCodeCacheable($statusCode) {
        if (!in_array($statusCode, self::$CACHEABLE_STATUS_CODES)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param string $HTTPMethod
     * @return booleanF
     */
    private function isHTTPMethodCacheable($HTTPMethod) {
        if (!in_array($HTTPMethod, self::$CACHEABLE_HTTP_METHODS)) {
            return false;
        }
    }

    /**
     * 
     * @param string $cacheControl
     * @return boolean
     */
    private function isCacheControlCacheable($cacheControl) {
        // parse CacheControl
        $pCC = $this->parseCacheControl($cacheControl);

        if (isset($pCC['private'], $pCC['no-store'], $pCC['no-cache'])) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @param type $cacheControl
     * @return array
     */
    private function parseCacheControl($cacheControl) {
        $arrayCacheControl = explode(', ', $cacheControl);
        $parsedCC = array();
        foreach ($arrayCacheControl as $value) {
            $pos = strpos($value, '=');
            if ($pos !== false) {
                $parsedCC[substr($value, 0, $pos)] = substr($value, $pos + 1);
            } else {
                $parsedCC[$value] = true;
            }
        }
        return $parsedCC;
    }

}

?>
