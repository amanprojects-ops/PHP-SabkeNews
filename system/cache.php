<?php
/**
 * File-based Cache System
 * 
 * Simple, lightweight caching system using the filesystem.
 * No external dependencies (no Redis, no Memcached needed).
 * 
 * Usage:
 *   $data = Cache::get('my_key');
 *   if ($data === false) {
 *       $data = expensiveQuery();
 *       Cache::set('my_key', $data, 3600);
 *   }
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

class Cache {
    
    /** @var string Cache directory path */
    private static $cacheDir = null;
    
    /**
     * Initialize cache directory
     * Creates directory if it doesn't exist
     */
    private static function init() {
        if (self::$cacheDir === null) {
            self::$cacheDir = defined('CACHE_DIR') ? CACHE_DIR : dirname(__DIR__) . '/cache';
        }
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
    }
    
    /**
     * Get cached data by key
     * 
     * @param string $key Cache key
     * @return mixed Cached data or false if expired/missing
     */
    public static function get($key) {
        if (!defined('CACHE_ENABLED') || !CACHE_ENABLED) {
            return false;
        }
        
        self::init();
        $file = self::getFilePath($key);
        
        if (!file_exists($file)) {
            return false;
        }
        
        // Check if cache has expired
        $ttl = defined('CACHE_TTL') ? CACHE_TTL : 3600;
        if ((time() - filemtime($file)) >= $ttl) {
            self::delete($key);
            return false;
        }
        
        $content = file_get_contents($file);
        if ($content === false) {
            return false;
        }
        
        $data = @unserialize($content);
        return ($data !== false) ? $data : false;
    }
    
    /**
     * Set cache data
     * 
     * @param string $key Cache key
     * @param mixed $data Data to cache (must be serializable)
     * @param int|null $ttl Time-to-live in seconds (uses CACHE_TTL if null)
     * @return bool Success status
     */
    public static function set($key, $data, $ttl = null) {
        if (!defined('CACHE_ENABLED') || !CACHE_ENABLED) {
            return false;
        }
        
        self::init();
        $file = self::getFilePath($key);
        
        return file_put_contents($file, serialize($data), LOCK_EX) !== false;
    }
    
    /**
     * Delete specific cache entry
     * 
     * @param string $key Cache key to delete
     * @return bool Success status
     */
    public static function delete($key) {
        self::init();
        $file = self::getFilePath($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }
    
    /**
     * Clear all cached data
     * 
     * @return int Number of files cleared
     */
    public static function clearAll() {
        self::init();
        $count = 0;
        
        $files = glob(self::$cacheDir . '/*.cache');
        if ($files) {
            foreach ($files as $file) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Generate file path for cache key
     * 
     * @param string $key Cache key
     * @return string Full file path
     */
    private static function getFilePath($key) {
        // Sanitize key to safe filename
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '_', $key);
        return self::$cacheDir . '/' . $safeKey . '.cache';
    }
}
