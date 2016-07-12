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
     * Assert that input looks like an alpha2 key.
     *
     * @param string $alpha2
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like an alpha2 key.
     */
    private function guardAgainstInvalidAlpha2($alpha2)
    {
        $this->guardAgainstInvalidString($alpha2, '$alpha2');
        if (!preg_match('/^[a-zA-Z]{2}$/', $alpha2)) {
            throw new \DomainException(sprintf('Not a valid alpha2 key: %s', $alpha2));
        }
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
     * Assert that input looks like an alpha3 key.
     *
     * @param string $alpha3
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like an alpha3 key.
     */
    private function guardAgainstInvalidAlpha3($alpha3)
    {
        $this->guardAgainstInvalidString($alpha3, '$alpha3');
        if (!preg_match('/^[a-zA-Z]{3}$/', $alpha3)) {
            throw new \DomainException(sprintf('Not a valid alpha3 key: %s', $alpha3));
        }
    }

    /**
     * Assert that input looks like a numeric key.
     *
     * @param string $numeric
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like a numeric key.
     */
    private function guardAgainstInvalidNumeric($numeric)
    {
        $this->guardAgainstInvalidString($numeric, '$numeric');
        if (!preg_match('/^[0-9]{3}$/', $numeric)) {
            throw new \DomainException(sprintf('Not a valid numeric key: %s', $numeric));
        }
    }
}
