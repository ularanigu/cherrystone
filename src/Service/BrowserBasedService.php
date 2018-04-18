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

use Ularanigu\Cherrystone\Exception\InvalidServiceConfig;
use Ularanigu\Cherrystone\Classification\Service;
use Ularanigu\Cherrystone\Utils\Checker;
use InvalidArgumentException;

/**
 * @class BrowserBasedService.
 */
class BrowserBasedService extends ServiceConfig implements Service
{
    
    /** @var array $errors An array of errors during service check. */
    private $errors = array();
    
    /** @var string $alias The short prefixed name for the service. */
    private $alias = null;
    
    /** @var array|null $serviceConfig The servce config. */
    private $serviceConfig = null;

    /**
     * Constructor
     *
     * @param string $alias         The short prefixed name for the service.
     * @param array  $serviceConfig The service config.
     *
     * @return void Return nothing.
     */
    public function __construct(
        string $alias = '',
        array $serviceConfig = array()
    ) {
        $this->check();
        $alias = trim($alias);
        if (!empty($alias)) {
            $this->alias = $alias;
        }
        if (!empty($serviceConfig)) {
            $this->validServiceConfig($serviceConfig);
            $this->serviceConfig = $serviceConfig;
        } else {
            $this->serviceConfig = array(
                'directives' => array(
                    'service.check_platform',
                    'service.check_browser'
                ),
                'logger' => true
            );
        }
    }
    
    /**
     * Check to see if the directive is valid.
     *
     * @link <https://secure.php.net/manual/en/function.array-key-exists.php>.
     * @link <https://secure.php.net/manual/en/function.array-push.php>.
     *
     * @param array $serviceConfig The service config.
     *
     * @throws InvalidServiceConfig If no directive was passed.
     * @throws InvalidServiceConfig If the directive is unknown or unsupported.
     *
     * @return void Return nothing.
     */
    private validServiceConfig(
        array $serviceConfig
    ): void {
        if (!array_key_exists('directives', $serviceConfig) || !is_array($serviceConfig['directives']) || empty($serviceConfig['directives'])) {
            throw new InvalidServiceConfig('No directive was passed.');
        }
        $used = array();
        foreach ($serviceConfig['directives'] as $directive) {
            if (!in_array($directive, static::$allowedDirectives, true)) {
                throw new InvalidServiceConfig('A directive was unsupported or unknown.');
            }
            if (in_array($directive, $used, true)) {
                throw new InvalidServiceConfig('A directive was repeated.');
            }
            array_push($used, $directive);
        }
        if (array_key_exists('logger', $serviceConfig) && !is_bool($serviceConfig['logger']) && !is_null($serviceConfig['logger'])) {
            throw new InvalidArgumentException('The logger key contains an invalid data type.');
        }
    }
    
    /**
     * Check to see if the browser user agents validate.
     *
     * @link <https://secure.php.net/manual/en/function.array-merge.php>.
     * @link <https://secure.php.net/manual/en/function.strcmp.php>.
     *
     * @param string $knownUserAgent The user agent that is known.
     * @param string $usersUserAgent The user agent that is from an unknown user.
     *
     * @return array Return an array of information about the validation.
     */
    public function validate(
        string $knownUserAgent,
        string $usersUserAgent
    ): array {
        $info = array();
        $directives = $this->serviceConfig['directives'];
        foreach ($directives as $directive) {
            $result = $this->stripUserAgent($directive, $knownUserAgent, $usersUserAgent);
            if (strcmp($result[0], $result[1]) === 0) {
                $info = array_merge($info, array(
                    $directive => array(
                        'status' => 'success'
                    )
                ));
            } else {
                $errors = array();
                if ($this->serviceConfig['logger']) {
                    $errors = $this->errors[$directive];
                }
                $info = array_merge($info, array(
                    $directive => array(
                        'status' => 'error',
                        'errors' => $errors
                    )
                ));
            }
        }
        return (array) $info;
    }
    
    /**
     * Strip the user agent into parts and return the part needed.
     *
     * @link <https://secure.php.net/manual/en/function.substr.php>.
     * @link <https://secure.php.net/manual/en/function.get-browser.php>.
     *
     * @param string $knownUserAgent The user agent that is known.
     * @param string $usersUserAgent The user agent that is from an unknown user.
     *
     * @return array Return the parts from both user agents and log correcty based on service config.
     */
    private function stripUserAgent(
        string $directive,
        string $knownUserAgent,
        string $usersUserAgent
    ): array {
        $knownUserAgentInfo = get_browser($knownUserAgent, true);
        $usersUserAgentInfo = get_browser($usersUserAgent, true);
        $len = strlen('service.check_');
        $directiveAlias = substr($directive, $len);
        return array(
            $knownUserAgentInfo[$directiveAlias],
            $usersUserAgentInfo[$directiveAlias]
        );
    }
    
    /**
     * Check to see if the get_browser() function works.
     *
     * @return void Return nothing.
     */
    private function check(): void {
        cs_check_gb();
    }
}
