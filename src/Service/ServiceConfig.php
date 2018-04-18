<?php
declare(strict_types=1);
/**
 * Cherrystone Authentication.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/LICENSE>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace Ularanigu\Cherrystone\Service;

/**
 * @class ServiceConfig.
 */
class ServiceConfig
{
    
    /** @var array $allowedDirectives An array of allowed directives. */
    protected static $allowedDirectives = array(
        'browser_directives' => array(
            'service.check_browser_name_regex',
            'service.check_browser_name_pattern',
            'service.check_parent',
            'service.check_platform',
            'service.check_browser',
            'service.check_version',
            'service.check_majorver',
            'service.check_minorver'
            'service.check_cssversion',
            'service.check_frames',
            'service.check_iframes',
            'service.check_tables',
            'service.check_cookies',
            'service.check_backgroundsounds',
            'service.check_vbscript',
            'service.check_javascript',
            'service.check_javaapplets',
            'service.check_activexcontrols',
            'service.check_cdf',
            'service.check_aol',
            'service.check_beta',
            'service.check_win16',
            'service.check_crawler',
            'service.check_stripper',
            'service.check_wap',
            'service.check_netclr'
        )
    );
}
