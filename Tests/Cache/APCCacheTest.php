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

use Dozoisch\CachedBuzzBundle\Cache\APCCache;

/**
 * Test class for the APCCache class
 *
 * @author hugo
 */
class APCCacheTest extends \PHPUnit_Framework_TestCase {

    private static $key = 'mykey123';
    private static $data1 = 'data1';
    private static $data2 = array('data1' => 'a', 'data2' => 'b', 'data3' => 'c');

    /**
     *
     * @var APCCache
     */
    private $cache;

    public function setUp() {
        if (!(extension_loaded('apc') && ini_get('apc.enabled') && ini_get('apc.enable_cli'))) {
            $this->markTestSkipped('The APC extension is not available.');
        }
        $this->cache = new APCCache();
    }

    public function testGet() {
        $this->assertFalse($this->cache->get(self::$key));
    }

    /**
     * @depends testGet
     */
    public function testSet() {
        $this->assertTrue($this->cache->set(self::$key, self::$data1));
        $this->assertSame(self::$data1, $this->cache->get(self::$key));

        $this->assertTrue($this->cache->set(self::$key, self::$data2));
        $this->assertSame(self::$data2, $this->cache->get(self::$key));
    }

    /**
     * @depends testSet
     */
    public function testDelete() {
        $this->cache->set(self::$key, self::$data1);
        $this->assertTrue($this->cache->delete(self::$key));
        $this->assertFalse($this->cache->get(self::$key));
    }

}

?>
