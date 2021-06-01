<?php declare(strict_types=1);
/**
 * Cherrystone Encryption.
 * A more secure web.
 *
 * @license <https://github.com/ularanigu/cherrystone/blob/master/license>.
 * @link    <https://github.com/ularanigu/cherrystone>.
 */

namespace CherryStone;

use InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The sodium password hasher.
 */
final class PasswordHasher extends AbstractPasswordHasher implements PasswordHasherInterface
{
    use PasswordLengthChecker;

    /** @var array $options The password hasher options. */
    private array $options = [];

    /**
     * Construct a new password hasher.
     *
     * @param mixed $passwordAlgo The password algo to use.
     * @param array $options      The password hasher options.
     *
     * @return void Returns nothing.
     */
    public function __construct(public mixed $passwordAlgo = \PASSWORD_DEFAULT, array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Set the password hasher options.
     *
     * @param array $options The password hasher options.
     *
     * @return PasswordHasherInterface Returns the password hasher.
     */
    public function setOptions(array $options = []): PasswordHasherInterface
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        if (!$resolver->isMissing('salt') && $this->passwordAlgo !== \PASSWORD_PBKDF2) {
            unset($this->options['salt']);
        }
        return $this;
    }

    /**
     * Compute the user's passwod hash.
     *
     * @param string $password The user's password.
     *
     * @return string|false Returns the hashed password, or false on failure. 
     */
    public function compute(string $password): string|false
    {
        if ($this->isPasswordTooLong($password)) {
            throw new InvalidArgumentException('The password supplied is too long.');
        }
        $generatedHash = \false;
        switch ($this->passwordAlgo) {
            case \PASSWORD_ARGON2I:
            case \PASSWORD_ARGON2ID:
            case \PASSWORD_BCRYPT:
            case \PASSWORD_DEFAULT:
                $generatedHash = \password_hash(
                    $password,
                    $this->passwordAlgo,
                    $this->options
                );
                break;
            case \PASSWORD_SODIUM:
                $generatedHash = \sodium_crypto_pwhash_str(
                    $password,
                    $this->options['sodium_crypto_pwhash_opslimit_interactive'],
                    $this->options['sodium_crypto_pwhash_memlimit_interactive']
                );
                break;
            case \PASSWORD_PBKDF2:
                $generatedHash = \hash_pbkdf2(
                    $this->options['algo'],
                    $password,
                    $this->options['salt'],
                    $this->options['iterations']
                );
                break;
        }
        \sodium_memzero($password);
        return $generatedHash;
    }

    /**
     * Verifies that a password matches a hash.
     *
     * @param string $password The user's password.
     * @param string $hash     A hash created by self::compute().
     *
     * @return bool Returns true if the password and hash match, or false otherwise. 
     */
    public function verify(string $password, string $hash): bool
    {
        $verified = \false;
        switch ($this->passwordAlgo) {
            case \PASSWORD_ARGON2I:
            case \PASSWORD_ARGON2ID:
            case \PASSWORD_BCRYPT:
            case \PASSWORD_DEFAULT:
                $verified = \password_verify(
                    $password,
                    $hash
                );
                break;
            case \PASSWORD_SODIUM:
                $verified = \sodium_crypto_pwhash_str_verify(
                    $hash,
                    $password
                );
                break;
            case \PASSWORD_PBKDF2:
                $verified = \hash_equals(
                    $this->compute($password),
                    $hash
                );
                break;
        }
        \sodium_memzero($password);
        return $verified;
    }
    /**
     * Checks if the given hash matches the options of the hasher.
     *
     * @param string $hash A hash created by self::compute().
     *
     * @return bool Returns true if the hash should be rehashed to match the current
     *              hasher algo and options, or false otherwise. 
     */
    public function needsRehash(string $hash): bool
    {
        switch ($this->passwordAlgo) {
            case \PASSWORD_ARGON2I:
            case \PASSWORD_ARGON2ID:
            case \PASSWORD_BCRYPT:
            case \PASSWORD_DEFAULT:
                return \password_needs_rehash(
                    $password,
                    $hash
                );
            case \PASSWORD_SODIUM:
                return \sodium_crypto_pwhash_str_needs_rehash(
                    $hash,
                    $this->options['sodium_crypto_pwhash_opslimit_interactive'],
                    $this->options['sodium_crypto_pwhash_memlimit_interactive']
                );
            case \PASSWORD_PBKDF2:
                return \true;
        }
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sodium_crypto_pwhash_opslimit_interactive' => \SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            'sodium_crypto_pwhash_memlimit_interactive' => \SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
            'memory_cost'                               => \PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost'                                 => \PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads'                                   => \PASSWORD_ARGON2_DEFAULT_THREADS,
            'cost'                                      => 12,
            'iterations'                                => 100000,
        ]);
        if ($this->passwordAlgo === \PASSWORD_PBKDF2) {
            $resolver->setRequired('algo');
            $resolver->setAllowedTypes('algo', 'string');
            $resolver->setAllowedValues('algo', \hash_algos());
            $resolver->setRequired('salt');
        }
        $resolver->setAllowedTypes('cost', 'int');
        $resolver->setAllowedTypes('salt', 'string');
        $resolver->setAllowedTypes('iterations', 'int');
    }
}
