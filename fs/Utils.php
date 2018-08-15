<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Firestorm;

/**
 * @class Utils.
 */
class Utils
{

    /**
     * Generate the token name.
     *
     * @return string The generated token name.
     */
    public static function generateTokenName(): string
    {
        return 'xsrf.validation.firestorm';
    }
}
