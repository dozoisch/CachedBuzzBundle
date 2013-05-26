<?php

namespace Dozoisch\CachedBuzzBundle\Cache;

use Buzz\Message\Response;
use Buzz\Message\Request;

/**
 * Description of CacheValidatorInterface
 *
 * @author hugo
 */
interface CacheValidatorInterface {

    /**
     * @param Request $request The request
     * @param Response $response The response
     * @return boolean
     */
    public function isCacheable(Request $request, Response $response);

    /**
     * @param Request $request The request
     * @return boolean
     */
    public function isRequestCacheable(Request $request);

    /**
     * @param Response $response The response
     * @return boolean
     */
    public function isResponseCacheable(Response $response);
    
    /**
     * 
     * @param Response $response The response
     * @return boolean
     */
    public function isExpired(Response $response);
}

?>
