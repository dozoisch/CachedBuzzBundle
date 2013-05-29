<?php

/*
 * This file is part of the Cached Buzz Bundle.
 *
 * (C) 2013 Hugo Dozois-Caouette
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dozoisch\CachedBuzzBundle\Tests\Cache;

use Dozoisch\CachedBuzzBundle\Cache\CacheValidator;
use Buzz\Message\Request;
use Buzz\Message\Response;

/**
 * CacheValidatorTest
 *
 * @author hugo
 */
class CacheValidatorTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var CacheValidator 
     */
    private $validator;
    private $request;
    private $response;

    public function setUp() {
        $this->validator = new CacheValidator();
    }

    public function setUpCacheableRequest() {
        //Putting on cacheable  info first
        $this->request = new Request();
        $this->request->setMethod('GET');
        $this->request->fromUrl('https://www.googleapis.com/oauth2/v1/certs');
    }

    public function setUpCacheableResponse() {
        $this->response = new Response();
        $headers = array(
            0 => 'HTTP/1.1 200 OK',
            'Date: Tue, 28 May 2013 19:16:02 GMT',
            'Content-Type: application/json; charset=UTF-8',
        );
        $this->response->setHeaders($headers);
        $this->response->getStatusCode();
    }

    public function testIsExpired() {
        $this->setUpCacheableResponse();
        $this->assertFalse($this->validator->isExpired($this->response));

        // Valid
        $this->response->setHeaders(array(
            'Expires: Tue, 28 May 2038 19:18:10 GMT',
        ));
        $this->assertFalse($this->validator->isExpired($this->response, 0), 'That should not be expired, 2038');

        // Invalid
        $this->response->setHeaders(array(
            'Expires: Tue, 28 May 2000 19:16:01 GMT',
        ));
        $this->assertTrue($this->validator->isExpired($this->response, 0), 'That should be expired, 2000');
        $this->response->setHeaders(array(
            'Expires: 0',
        ));
        $this->assertTrue($this->validator->isExpired($this->response, 0), 'That should be expired, 0');
    }

    public function testIsStatusCodeCacheable() {
        $cacheables = array('200', '203', '204', '205', '300', '301', '410');
        $notCacheables = array('201', '202', '206',
            '302', '303', '304', '305', '307',
            '400', '401', '402', '403', '404', '405', '406', '407', '408', '409', '411', '412', '413', '414', '415', '416', '417',
            '500', '501', '502', '503', '504', '505'
        );
        foreach ($cacheables as $code) {
            $this->assertTrue($this->validator->isStatusCodeCacheable($code), "The code $code should be cacheable");
        }
        foreach ($notCacheables as $code) {
            $this->assertFalse($this->validator->isStatusCodeCacheable($code), "The code $code should not be cacheable");
        }
    }

    public function testIsHTTPMethodCacheable() {
        $cacheables = array('GET', 'HEAD');
        $notCacheables = array('POST', 'PUT', 'DELETE', 'TRACE');
        foreach ($cacheables as $method) {
            $this->assertTrue($this->validator->isHTTPMethodCacheable($method), "The method $method should be cacheable");
        }
        foreach ($notCacheables as $method) {
            $this->assertFalse($this->validator->isHTTPMethodCacheable($method), "The method $method should not be cacheable");
        }
    }

    /**
     * @depends testIsHTTPMethodCacheable
     */
    public function testIsRequestCacheable() {
        $this->setUpCacheableRequest();
        $this->assertTrue($this->validator->isRequestCacheable($this->request), 'Request is supposed to be cacheable');

        //Headers contains credential
        $this->setUpCacheableRequest();
        $this->request->addHeader('Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==');
        $this->assertFalse($this->validator->isRequestCacheable($this->request), 'The header Authorization should not be cacheable');
    }

    /**
     * @depends testIsExpired
     * @depends testIsStatusCodeCacheable
     */
    public function testIsResponseCacheable() {
        $this->setUpCacheableResponse();
        $this->assertTrue($this->validator->isResponseCacheable($this->response, 'Response is supposed to be cacheable'));

        // Bad headers
        $this->response->addHeader('Vary: *');
        $this->assertFalse($this->validator->isResponseCacheable($this->response, 'The header Vary: * should not be cacheable'));
        $this->setUpCacheableResponse();
        $this->response->addHeader('Etag: "686897696a7c876b7e"');
        $this->assertFalse($this->validator->isResponseCacheable($this->response, 'The header Etag should not be cacheable'));
        $this->setUpCacheableResponse();
        $this->response->addHeader('Pragma: no-cache');
        $this->assertFalse($this->validator->isResponseCacheable($this->response, 'The header Pragma: no-cache should not be cacheable'));
    }

    /**
     * @depends testIsRequestCacheable
     * @depends testIsResponseCacheable
     */
    public function testIsCacheable() {
        
    }

}

?>
