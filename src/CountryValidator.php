<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use InvalidArgumentException;

class CountryValidator
{
    use ValidatorTrait;

    /**
     * new Instance
     * @param mixed $countries an iterable object/structure where each item
     *                         represents a valid country array data
     */
    public function validateMany(array $countries = [])
    {
        foreach ($countries as &$country) {
            $country = $this->validateOne($country);
        }
        unset($country);

        return $countries;
    }

    /**
     * Validate and normalize a country array
     *
     * @param array $country
     *
     * @throws InvalidArgumentException if required indexes are missing
     *
     * @return array
     */
    public function validateOne(array $country)
    {
        if (!isset(
            $country[ISO3166::KEY_ALPHA2],
            $country[ISO3166::KEY_ALPHA3],
            $country[ISO3166::KEY_NUMERIC],
            $country['currency'],
            $country['name']
        )) {
            throw new InvalidArgumentException(sprintf(
                'Invalid value for $country, one of the following required key is missing: %s',
                implode(', ', [ISO3166::KEY_ALPHA2, ISO3166::KEY_ALPHA3, ISO3166::KEY_NUMERIC, 'currency', 'name'])
            ));
        }

        return [
            ISO3166::KEY_ALPHA2 => $this->validateAlpha2($country[ISO3166::KEY_ALPHA2]),
            ISO3166::KEY_ALPHA3 => $this->validateAlpha3($country[ISO3166::KEY_ALPHA3]),
            ISO3166::KEY_NUMERIC => $this->validateNumeric($country[ISO3166::KEY_NUMERIC]),
            'currency' => $this->validateCurrencies($country['currency']),
            'name' => $this->validateName($country['name']),
        ];
    }
}
