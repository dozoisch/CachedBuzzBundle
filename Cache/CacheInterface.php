<?php

namespace Dozoisch\CachedBuzzBundle\Cache;

/**
 *
 * @author dozoisch
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
