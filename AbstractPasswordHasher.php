<?php declare(strict_types=1);
/**
 * Cherrystone Encryption.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/license>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace CherryStone;

use ArrayAccess;
use RuntimeException;

/**
 * The abstract password hasher.
 */
abstract class AbstractPasswordHasher implements ArrayAccess
{
    /**
     * Type-Hints are not needed for this method.
     * This method is not supported.
     */
    public function offsetExists($offset)
    {
        throw new RuntimeException('The ArrayAccess `exists` method is not supported.');
    }

    /**
     * Compute a hash.
     * Type-Hints are not needed for this method.
     *
     * @param mixed $offset The password to hash.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->compute($offset);
    }

    /**
     * Type-Hints are not needed for this method.
     * This method is not supported.
     */
    public function offsetSet($offset, $value)
    {
        throw new RuntimeException('The ArrayAccess `set` method is not supported.');
    }

    /**
     * Type-Hints are not needed for this method.
     * This method is not supported.
     */
    public function offsetUnset($offset)
    {
        throw new RuntimeException('The ArrayAccess `unset` method is not supported.');
    }
}
