<?php

namespace Dozoisch\CachedBuzzBundle\Cache;

/**
 * A cache implementation using APC.
 *
 * @author hugo
 */
class APCCache implements CacheInterface {

    public function delete($key) {
        return @apc_delete($key);
    }

    public function get($key) {
        return @apc_exists($key) ? unserialize(@apc_fetch($key)) : false;
    }

    public function set($key, $data) {
        return @apc_store($key, serialize($data));
    }

}

?>
