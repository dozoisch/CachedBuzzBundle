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
 * Description of CacheValidatorTest
 *
 * @author hugo
 */
class CacheValidatorTest extends \PHPUnit_Framework_TestCase {

    private $validator;

    public function setUp() {
        $this->validator = new CacheValidator();

        $response = new Response();
        $headers = array();
        $headers['cache-control'] = 'public, max-age=18768, must-revalidate, no-transform';
        $response->setHeaders($headers);
    }

    public function testIsResponseCacheable() {
        $this->assertTrue(true);
    }

    public function testIsAnswerCacheable() {
        $this->assertTrue(true);
    }

    /**
     * @depends testIsAnswerCacheable
     * @depends testIsResponseCacheable
     */
    public function testIsCacheable() {
        
    }

    public function testIsExpired() {
        
    }

}

?>
