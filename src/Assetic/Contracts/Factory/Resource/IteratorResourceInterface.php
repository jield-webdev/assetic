<?php namespace Assetic\Contracts\Factory\Resource;

use IteratorAggregate;

/**
 * A resource is something formulae can be loaded from.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
interface IteratorResourceInterface extends ResourceInterface, IteratorAggregate
{
}
