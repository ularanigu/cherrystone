<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Cherrystone;

/**
 * @function version().
 */
function version(): string
{
    return \json_encode(array(\CHERRYSTONE_VERSION_ID, \CHERRYSTONE_VERSION));
}
