<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

trait KeyValidators
{
    /**
     * @var array
     */
    private $requiredKeys = [
        ISO3166::KEY_ALPHA2 => 'guardAgainstInvalidAlpha2',
        ISO3166::KEY_ALPHA3 => 'guardAgainstInvalidAlpha3',
        ISO3166::KEY_NUMERIC => 'guardAgainstInvalidNumeric',
    ];

    /**
     * @param array $entry
     *
     * @throws \DomainException if given data entry does not have all the required keys.
     */
    private function assertEntryHasRequiredKeys(array $entry)
    {
        foreach ($this->requiredKeys as $key => $filter) {
            if (!isset($entry[$key])) {
                throw new \DomainException(sprintf('Each data entry must have a valid %s key.', $key));
            }

            call_user_func([$this, $filter], $entry[$key]);
        }
    }

    /**
     * Assert that input looks like an alpha2 key.
     *
     * @param string $alpha2
     */
    private function guardAgainstInvalidAlpha2($alpha2)
    {
        $this->assertValidRequiredKey($alpha2, 'alpha2', '/^[a-zA-Z]{2}$/');
    }

    /**
     * Assert that input validate a required key.
     *
     * @param string $input input value
     * @param string $param input name
     * @param string $regexp domain specific regular expression
     *
     */
    private function assertValidRequiredKey($input, $param, $regexp)
    {
        $this->guardAgainstInvalidString($input, '$'.$param);
        $this->guardAgainstInvalidRegexp($input, $param, $regexp);
    }

    /**
     * Assert that input is a string.
     *
     * @param string $input input value
     * @param string $param input name
     *
     * @throws \InvalidArgumentException if input is not a string.
     */
    private function guardAgainstInvalidString($input, $param)
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s to be of type string, got: %s',
                $param,
                gettype($input)
            ));
        }
    }

    /**
     * Assert that the input string validate the given regular expression.
     *
     * @param string $input input value
     * @param string $param input name
     * @param string $regexp domain specific regular expression
     *
     * @throws \DomainException if input does not validate the expression.
     */
    private function guardAgainstInvalidRegexp($input, $param, $regexp)
    {
        if (!preg_match($regexp, $input)) {
            throw new \DomainException(sprintf('Not a valid %s key: %s', $param, $input));
        }
    }

    /**
     * Assert that input looks like an alpha3 key.
     *
     * @param string $alpha3
     */
    private function guardAgainstInvalidAlpha3($alpha3)
    {
        $this->assertValidRequiredKey($alpha3, 'alpha3', '/^[a-zA-Z]{3}$/');
    }

    /**
     * Assert that input looks like a numeric key.
     *
     * @param string $numeric
     */
    private function guardAgainstInvalidNumeric($numeric)
    {
        $this->assertValidRequiredKey($numeric, 'numeric', '/^[0-9]{3}$/');
    }
}
