<?php namespace Assetic\Cache;

use Assetic\Contracts\Cache\CacheInterface;
use RuntimeException;

/**
 * A simple filesystem cache.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class FilesystemCache implements CacheInterface
{
    private $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function has($key)
    {
        return file_exists($this->dir . '/' . $key);
    }

    public function get($key)
    {
        $path = $this->dir . '/' . $key;

        if (!file_exists($path)) {
            throw new RuntimeException('There is no cached value for ' . $key);
        }

        return file_get_contents($path);
    }

    public function set($key, $value)
    {
        if (!is_dir($this->dir) && !mkdir($concurrentDirectory = $this->dir, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException('Unable to create directory ' . $this->dir);
        }

        $path = $this->dir . '/' . $key;

        if (false === @file_put_contents($path, $value)) {
            throw new RuntimeException('Unable to write file ' . $path);
        }
    }

    public function remove($key)
    {
        $path = $this->dir . '/' . $key;

        if (file_exists($path) && false === @unlink($path)) {
            throw new RuntimeException('Unable to remove file ' . $path);
        }
    }
}
