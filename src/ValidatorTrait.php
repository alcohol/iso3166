<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use DomainException;
use InvalidArgumentException;
use OutOfBoundsException;

trait ValidatorTrait
{
    /**
     * Validate and normalized ISO3166-1-alpha2-code
     *
     * @param  string $input
     *
     * @throws DomainException if input does not conform to alpha2 format.
     *
     * @return string
     */
    protected function validateAlpha2($input)
    {
        if (!preg_match('/^[a-zA-Z]{2}$/', $input)) {
            throw new DomainException('Not a valid alpha2: ' . $input);
        }

        return strtoupper($input);
    }

    /**
     * Validate and normalized ISO3166-1-alpha3-code
     *
     * @param  string $input
     *
     * @throws DomainException if input does not conform to alpha3 format.
     *
     * @return string
     */
    protected function validateAlpha3($input)
    {
        if (!preg_match('/^[a-zA-Z]{3}$/', $input)) {
            throw new DomainException('Not a valid alpha3: ' . $input);
        }

        return strtoupper($input);
    }

    /**
     * Validate and normalized ISO3166-1-numeric-code
     *
     * @param  string $input
     *
     * @throws DomainException if input does not conform to numeric format.
     *
     * @return string
     */
    protected function validateNumeric($input)
    {
        if (!preg_match('/^[0-9]{3}$/', $input)) {
            throw new DomainException('Not a valid numeric: ' . $input);
        }

        return sprintf('%03d', $input);
    }

    /**
     * Validate and normalized ISO4217-alpha3-code
     *
     * @param mixed $input a string or an iterable object
     *
     * @return string|array
     */
    protected function validateCurrencies($input)
    {
        if (is_array($input)) {
            $list = [];
            foreach ($input as $currency) {
                $list[] = $this->validateCurrency($currency);
            }
            return $list;
        }

        return $this->validateCurrency($input);
    }

    /**
     * Validate and normalized ISO4217-alpha3-code
     *
     * @param string $input a string or an iterable object
     *
     * @throws DomainException if input does not conform to ISO4217 alpha3 format.
     *
     * @return string
     */
    protected function validateCurrency($input)
    {
        if (!preg_match('/^[a-zA-Z]{3}$/', $input)) {
            throw new DomainException('Not a valid ISO4217 currency code: ' . $input);
        }

        return strtoupper($input);
    }

    /**
     * Validate and normalized the country name
     *
     * @param string $str a string or an iterable object
     *
     * @throws DomainException if input is empty
     *
     * @return string
     */
    protected function validateName($input)
    {
        if (is_string($input) || (is_object($input) && !method_exists($input, '__toString'))) {
            $input = trim((string) $input);
            if ('' !== $input) {
                return $input;
            }

            throw new DomainException('A country name can not be empty');
        }

        throw new InvalidArgumentException(sprintf(
            'Expected data to be a string; received "%s"',
            (is_object($input) ? get_class($input) : gettype($input))
        ));
    }
}
