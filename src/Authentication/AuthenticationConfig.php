<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Cherrystone\Authentication;

/**
 * @class AuthenticationConfig.
 */
class AuthenticationConfig
{

    /** @var array $authenticationConfig The authentication config. */
    protected static $authenticationConfig = array(
        'directives' => array(
            'auth.use_strict_mode',
            'auth.use_service_provider'
            'auth.use_internal_messanger',
            'auth.log_communications'
        )
    );
}
