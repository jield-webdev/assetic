<?php namespace Assetic\Util;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * An object that can be used as either a string or array.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class TraversableString implements IteratorAggregate, Countable
{
    private $one;
    private $many;

    public function __construct($one, array $many)
    {
        $this->one  = $one;
        $this->many = $many;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->many);
    }

    public function count()
    {
        return count($this->many);
    }

    public function __toString()
    {
        return (string)$this->one;
    }
}
