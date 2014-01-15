<?php

/**
 * XCache
 *
 * @package XCache
 */

class Component_XCache {
    
    /**
     * set 
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function set($name, $value, $expire = null)
    {
        xcache_set($name, $value, $expire);
    }

    /**
     * get 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function get($name)
    {
        return xcache_get($name);
    }

    /**
     * __isset 
     * 
     * @param mixed $name 
     * @access public
     * @return bool
     */
    public function __isset($name)
    {
        return xcache_isset($name);
    }

    /**
     * __unset 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function remove($name)
    {
        xcache_unset($name);
    }
    
}