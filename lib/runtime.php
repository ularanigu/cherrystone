<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

define('CHERRYSTONE_VERSION_ID', '100000000');
define('CHERRYSTONE_VERSION', '1.0.0');

use Ularanigu\Firestorm\Exception\XSRFException;
use Ularanigu\Firestorm\Security\XSRFValidation;

if (isset($_SERVER['REQUEST_METHOD'])) {
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    if (in_array($httpMethod, array('POST', 'GET'))) {
        try {
            (new XSRFValidation)->checkToken();
        } catch (XSRFException $e) {
            echo (new Ularanigu\Firestorm\SecurityView)->show('colorful');
            exit();
        }
    }
}
