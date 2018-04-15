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
 * @class Authentication.
 */
class Authentication implements AuthenticationInterface
{

    /**
     * @var string|null $format The authentication format.
     */
    private $format = \null;

    /**
     * Constructor.
     *
     * @param array $options The authentication options.
     *
     * @return void Return nothing.
     */
    public function __construct(array $options)
    {
        if (!empty($options)) {
            $this->format = isset($options['format']) && \in_array($options['format'], \AUTHENTICATION_FORMATS, true) ? $options : \null;
        }
    }
}
