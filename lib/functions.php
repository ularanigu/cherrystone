<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

function cs_check_gb(): void
{
    if (!class_exists('Ularanigu\Firestorm\Report\Active')) {
        if (!function_exists('get_browser')) {
            trigger_error(
                'The get_browser() function does not exists.',
                E_USER_ERROR
            );
        } elseif (!file_exists(ini_get('browscap'))) {
            trigger_error(
                'The browscap leads to a file that does not exist.',
                E_USER_ERROR
            );
        } elseif (strcmp('browscap.ini', ini_get('browscap')) !== 0) {
            trigger_error(
                'The browscap leads to a file that is not an ini configuration file or it has an invalid name.',
                E_USER_ERROR
            );
        } else {
        }
    } else {
        return 'use_firestorm_polyfill'
    }
}
