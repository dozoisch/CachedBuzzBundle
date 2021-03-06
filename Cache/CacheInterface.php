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

/**
 * 
 * @author dozoisch
 * 
 * The class implementing this interface provides a way to save and retrieve data. 
 * The persistence time of the data depends of the implementation. 
 * 
 * For example, an implementation using files would be really persistent and an implementation using
 * APC would depend on the web server.
 */
interface CacheInterface {

    /**
     * Retrieves the data cached by the given key or false if the key doesn't exists or is expired.
     * 
     * @param string $key the key of the data
     * @return mixed|false
     */
    public function get($key);

    /**
     * Saves the data for the given key. The data is being serialized
     * 
     * @param string $key
     * @param * $data
     * @return boolean success
     */
    public function set($key, $data);

    /**
     * Remove the key and the data from the cache
     * 
     * @param string $key
     * @return boolean success
     */
    public function delete($key);
}

?>
