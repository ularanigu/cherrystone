<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Cherrystone\Exception;

use RuntimeException;

/**
 * @class InvalidAuthenticationConfigException.
 */
class InvalidAuthenticationConfigException extends RuntimeException implements ExceptionInterface
{
}
