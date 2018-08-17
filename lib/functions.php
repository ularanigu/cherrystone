<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

/**
 * @function version().
 */
function version(): string
{
    return json_encode([
        'cherrystone' => [
            CHERRYSTONE_VERSION_ID,
            CHERRYSTONE_VERSION
        ],
        'firestorm' => [
            FIRESTORM_VERSION_ID,
            FIRESTORM_VERSION
        ],
    ]);
}
