<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Firestorm\Security;

use Ularanigu\Firestorm\Utils;
use Ularanigu\Firestorm\Exception\XSRFException;

/**
 * @class XSRFValidation.
 */
class XSRFValidation extends Utils
{

    /** @var string $xsrfField The xsrf field. */
    private static $xsrfField;

    /**
     *
     *
     *
     */
    public function checkToken(): void
    {
    }

    /**
     * Generate the html field.
     *
     * @param string $tokenName The token name.
     * @param string $token     The token.
     *
     * @return string The html xsrf field.
     */
    private function xsrfField(string $tokenName, string $token): string
    {
        return '<input id="xsrf.auth" name="' . $tokenName . '" type="hidden" value="' . $token . '" />'
    }
}
