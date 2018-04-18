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

use Ularanigu\Cherrystone\Exception\InvalidServiceConfigException;
use Ularanigu\Cherrystone\Classification\Service;
use InvalidArgumentException;

/**
 * @class BrowserBasedService.
 */
class BrowserBasedService extends ServiceConfig implements Service
{
    
    /** @var array $errors An array of errors during service check. */
    private $errors = array();
    
    /** @var string|null $alias The short prefixed name for the service. */
    public $alias = null;
    
    /** @var array|null $serviceConfig The servce config. */
    private $serviceConfig = null;

    /**
     * Constructor
     *
     * @param array  $serviceConfig The service config.
     * @param string $alias         The short prefixed name for the service.
     *
     * @return void Return nothing.
     */
    public function __construct(array $serviceConfig = array(), string $alias = '', bool $checkReqs = false)
    {
        if ($checkReqs) {
            $this->check();
        }
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
     * Check to see if the service config is valid.
     *
     * @link <https://secure.php.net/manual/en/function.array-key-exists.php>.
     * @link <https://secure.php.net/manual/en/function.array-push.php>.
     *
     * @param array $serviceConfig The service config.
     *
     * @throws InvalidServiceConfigException If no directive was passed.
     * @throws InvalidServiceConfigException If the directive is unknown or unsupported.
     * @throws InvalidServiceConfigException If a directive was repeated.
     * @throws InvalidArgumentException      If the logger key contains an invalid data type.
     *
     * @return void Return nothing.
     */
    private function validServiceConfig(array $serviceConfig): void
    {
        if (!array_key_exists('directives', $serviceConfig) || !is_array($serviceConfig['directives']) || empty($serviceConfig['directives'])) {
            throw new InvalidServiceConfigException('No directive was passed.');
        }
        $used = array();
        foreach ($serviceConfig['directives'] as $directive) {
            if (!in_array($directive, static::$allowedDirectives['browser_directives'], true)) {
                throw new InvalidServiceConfigException('A directive was unsupported or unknown.');
            }
            if (in_array($directive, $used, true)) {
                throw new InvalidServiceConfigException('A directive was repeated.');
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
    public function validate(string $knownUserAgent, string $usersUserAgent): array
    {
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
    private function stripUserAgent(string $directive, string $knownUserAgent, string $usersUserAgent): array
    {
        $knownUserAgentInfo = get_browser($knownUserAgent, true);
        $usersUserAgentInfo = get_browser($usersUserAgent, true);
        $len = strlen('service.check_');
        $directiveAlias = substr($directive, $len);
        return array(
            (string) $knownUserAgentInfo[$directiveAlias],
            (string) $usersUserAgentInfo[$directiveAlias]
        );
    }
    
    /**
     * Check to see if the get_browser() function works.
     *
     * @return void Return nothing.
     */
    private function check(): void
    {
        cs_check_gb();
    }
}
