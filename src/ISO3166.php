<?php

declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\Exception\DomainException;
use League\ISO3166\Exception\OutOfBoundsException;

/** @implements \IteratorAggregate<string, array> */
final class ISO3166 implements \Countable, \IteratorAggregate, ISO3166DataProvider
{
    /** @var string */
    public const KEY_ALPHA2 = 'alpha2';
    /** @var string */
    public const KEY_ALPHA3 = 'alpha3';
    /** @var string */
    public const KEY_NUMERIC = 'numeric';
    /** @var string */
    public const KEY_NAME = 'name';
    /** @var string[] */
    private array $keys = [self::KEY_ALPHA2, self::KEY_ALPHA3, self::KEY_NUMERIC, self::KEY_NAME];

    /**
     * @param array<array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }> $countries replace default dataset with given array
     */
    public function __construct(array $countries = [])
    {
        if ([] !== $countries) {
            $this->countries = $countries;
        }
    }

    /**
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    public function name(string $name): array
    {
        return $this->lookup(self::KEY_NAME, $name);
    }

    /**
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    public function alpha2(string $alpha2): array
    {
        Guards::guardAgainstInvalidAlpha2($alpha2);

        return $this->lookup(self::KEY_ALPHA2, $alpha2);
    }

    /**
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    public function alpha3(string $alpha3): array
    {
        Guards::guardAgainstInvalidAlpha3($alpha3);

        return $this->lookup(self::KEY_ALPHA3, $alpha3);
    }

    /**
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    public function numeric(string $numeric): array
    {
        Guards::guardAgainstInvalidNumeric($numeric);

        return $this->lookup(self::KEY_NUMERIC, $numeric);
    }

    /**
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    public function exactName(string $name): array
    {
        $value = mb_strtolower($name);

        foreach ($this->countries as $country) {
            $comparison = mb_strtolower($country[self::KEY_NAME]);

            if ($value === $comparison) {
                return $country;
            }
        }

        throw new OutOfBoundsException(sprintf('No "%s" key found matching: %s', self::KEY_NAME, $value));
    }

    /**
     * @return array<array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }>
     */
    public function all(): array
    {
        return $this->countries;
    }

    /**
     * @param 'name'|'alpha2'|'alpha3'|'numeric' $key
     *
     * @throws \League\ISO3166\Exception\DomainException if an invalid key is specified
     *
     * @return \Generator<string, array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }>
     */
    public function iterator(string $key = self::KEY_ALPHA2): \Generator
    {
        if (!in_array($key, $this->keys, true)) {
            throw new DomainException(sprintf('Invalid value for $key, got "%s", expected one of: %s', $key, implode(', ', $this->keys)));
        }

        foreach ($this->countries as $country) {
            yield $country[$key] => $country;
        }
    }

    /**
     * @see \Countable
     *
     * @internal
     */
    public function count(): int
    {
        return count($this->countries);
    }

    /**
     * @return \Generator<array<string, string|array<string>>>
     *
     * @see \IteratorAggregate
     *
     * @internal
     */
    public function getIterator(): \Generator
    {
        foreach ($this->countries as $country) {
            yield $country;
        }
    }

    /**
     * Lookup ISO3166-1 data by given identifier.
     *
     * Looks for a match against the given key for each entry in the dataset.
     *
     * @param 'name'|'alpha2'|'alpha3'|'numeric' $key
     *
     * @throws \League\ISO3166\Exception\OutOfBoundsException if key does not exist in dataset
     *
     * @return array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }
     */
    private function lookup(string $key, string $value): array
    {
        $value = mb_strtolower($value);

        foreach ($this->countries as $country) {
            $comparison = mb_strtolower($country[$key]);

            if ($value === $comparison || $value === mb_substr($comparison, 0, mb_strlen($value))) {
                return $country;
            }
        }

        throw new OutOfBoundsException(sprintf('No "%s" key found matching: %s', $key, $value));
    }

    /**
     * Default dataset.
     *
     * @var array<array{
     *     name: string,
     *     alpha2: string,
     *     alpha3: string,
     *     numeric: numeric-string,
     *     currency: string[],
     *     continent: string
     * }>
     */
    private array $countries = [
        [
            'name' => 'Afghanistan',
            'alpha2' => 'AF',
            'alpha3' => 'AFG',
            'numeric' => '004',
            'currency' => [
                'AFN',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Åland Islands',
            'alpha2' => 'AX',
            'alpha3' => 'ALA',
            'numeric' => '248',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Albania',
            'alpha2' => 'AL',
            'alpha3' => 'ALB',
            'numeric' => '008',
            'currency' => [
                'ALL',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Algeria',
            'alpha2' => 'DZ',
            'alpha3' => 'DZA',
            'numeric' => '012',
            'currency' => [
                'DZD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'American Samoa',
            'alpha2' => 'AS',
            'alpha3' => 'ASM',
            'numeric' => '016',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Andorra',
            'alpha2' => 'AD',
            'alpha3' => 'AND',
            'numeric' => '020',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Angola',
            'alpha2' => 'AO',
            'alpha3' => 'AGO',
            'numeric' => '024',
            'currency' => [
                'AOA',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Anguilla',
            'alpha2' => 'AI',
            'alpha3' => 'AIA',
            'numeric' => '660',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Antarctica',
            'alpha2' => 'AQ',
            'alpha3' => 'ATA',
            'numeric' => '010',
            'currency' => [
                'ARS',
                'AUD',
                'BGN',
                'BRL',
                'BYR',
                'CLP',
                'CNY',
                'CZK',
                'EUR',
                'GBP',
                'INR',
                'JPY',
                'KRW',
                'NOK',
                'NZD',
                'PEN',
                'PKR',
                'PLN',
                'RON',
                'RUB',
                'SEK',
                'UAH',
                'USD',
                'UYU',
                'ZAR',
            ],
            'continent' => 'Antarctica',
        ],
        [
            'name' => 'Antigua and Barbuda',
            'alpha2' => 'AG',
            'alpha3' => 'ATG',
            'numeric' => '028',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Argentina',
            'alpha2' => 'AR',
            'alpha3' => 'ARG',
            'numeric' => '032',
            'currency' => [
                'ARS',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Armenia',
            'alpha2' => 'AM',
            'alpha3' => 'ARM',
            'numeric' => '051',
            'currency' => [
                'AMD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Aruba',
            'alpha2' => 'AW',
            'alpha3' => 'ABW',
            'numeric' => '533',
            'currency' => [
                'AWG',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Australia',
            'alpha2' => 'AU',
            'alpha3' => 'AUS',
            'numeric' => '036',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Austria',
            'alpha2' => 'AT',
            'alpha3' => 'AUT',
            'numeric' => '040',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Azerbaijan',
            'alpha2' => 'AZ',
            'alpha3' => 'AZE',
            'numeric' => '031',
            'currency' => [
                'AZN',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Bahamas',
            'alpha2' => 'BS',
            'alpha3' => 'BHS',
            'numeric' => '044',
            'currency' => [
                'BSD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Bahrain',
            'alpha2' => 'BH',
            'alpha3' => 'BHR',
            'numeric' => '048',
            'currency' => [
                'BHD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Bangladesh',
            'alpha2' => 'BD',
            'alpha3' => 'BGD',
            'numeric' => '050',
            'currency' => [
                'BDT',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Barbados',
            'alpha2' => 'BB',
            'alpha3' => 'BRB',
            'numeric' => '052',
            'currency' => [
                'BBD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Belarus',
            'alpha2' => 'BY',
            'alpha3' => 'BLR',
            'numeric' => '112',
            'currency' => [
                'BYN',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Belgium',
            'alpha2' => 'BE',
            'alpha3' => 'BEL',
            'numeric' => '056',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Belize',
            'alpha2' => 'BZ',
            'alpha3' => 'BLZ',
            'numeric' => '084',
            'currency' => [
                'BZD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Benin',
            'alpha2' => 'BJ',
            'alpha3' => 'BEN',
            'numeric' => '204',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Bermuda',
            'alpha2' => 'BM',
            'alpha3' => 'BMU',
            'numeric' => '060',
            'currency' => [
                'BMD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Bhutan',
            'alpha2' => 'BT',
            'alpha3' => 'BTN',
            'numeric' => '064',
            'currency' => [
                'BTN',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Bolivia (Plurinational State of)',
            'alpha2' => 'BO',
            'alpha3' => 'BOL',
            'numeric' => '068',
            'currency' => [
                'BOB',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Bonaire, Sint Eustatius and Saba',
            'alpha2' => 'BQ',
            'alpha3' => 'BES',
            'numeric' => '535',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Bosnia and Herzegovina',
            'alpha2' => 'BA',
            'alpha3' => 'BIH',
            'numeric' => '070',
            'currency' => [
                'BAM',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Botswana',
            'alpha2' => 'BW',
            'alpha3' => 'BWA',
            'numeric' => '072',
            'currency' => [
                'BWP',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Bouvet Island',
            'alpha2' => 'BV',
            'alpha3' => 'BVT',
            'numeric' => '074',
            'currency' => [
                'NOK',
            ],
            'continent' => 'Antarctica',
        ],
        [
            'name' => 'Brazil',
            'alpha2' => 'BR',
            'alpha3' => 'BRA',
            'numeric' => '076',
            'currency' => [
                'BRL',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'British Indian Ocean Territory',
            'alpha2' => 'IO',
            'alpha3' => 'IOT',
            'numeric' => '086',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Brunei Darussalam',
            'alpha2' => 'BN',
            'alpha3' => 'BRN',
            'numeric' => '096',
            'currency' => [
                'BND',
                'SGD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Bulgaria',
            'alpha2' => 'BG',
            'alpha3' => 'BGR',
            'numeric' => '100',
            'currency' => [
                'BGN',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Burkina Faso',
            'alpha2' => 'BF',
            'alpha3' => 'BFA',
            'numeric' => '854',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Burundi',
            'alpha2' => 'BI',
            'alpha3' => 'BDI',
            'numeric' => '108',
            'currency' => [
                'BIF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Cabo Verde',
            'alpha2' => 'CV',
            'alpha3' => 'CPV',
            'numeric' => '132',
            'currency' => [
                'CVE',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Cambodia',
            'alpha2' => 'KH',
            'alpha3' => 'KHM',
            'numeric' => '116',
            'currency' => [
                'KHR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Cameroon',
            'alpha2' => 'CM',
            'alpha3' => 'CMR',
            'numeric' => '120',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Canada',
            'alpha2' => 'CA',
            'alpha3' => 'CAN',
            'numeric' => '124',
            'currency' => [
                'CAD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Cayman Islands',
            'alpha2' => 'KY',
            'alpha3' => 'CYM',
            'numeric' => '136',
            'currency' => [
                'KYD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Central African Republic',
            'alpha2' => 'CF',
            'alpha3' => 'CAF',
            'numeric' => '140',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Chad',
            'alpha2' => 'TD',
            'alpha3' => 'TCD',
            'numeric' => '148',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Chile',
            'alpha2' => 'CL',
            'alpha3' => 'CHL',
            'numeric' => '152',
            'currency' => [
                'CLP',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'China',
            'alpha2' => 'CN',
            'alpha3' => 'CHN',
            'numeric' => '156',
            'currency' => [
                'CNY',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Christmas Island',
            'alpha2' => 'CX',
            'alpha3' => 'CXR',
            'numeric' => '162',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Cocos (Keeling) Islands',
            'alpha2' => 'CC',
            'alpha3' => 'CCK',
            'numeric' => '166',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Colombia',
            'alpha2' => 'CO',
            'alpha3' => 'COL',
            'numeric' => '170',
            'currency' => [
                'COP',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Comoros',
            'alpha2' => 'KM',
            'alpha3' => 'COM',
            'numeric' => '174',
            'currency' => [
                'KMF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Congo',
            'alpha2' => 'CG',
            'alpha3' => 'COG',
            'numeric' => '178',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Congo (Democratic Republic of the)',
            'alpha2' => 'CD',
            'alpha3' => 'COD',
            'numeric' => '180',
            'currency' => [
                'CDF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Cook Islands',
            'alpha2' => 'CK',
            'alpha3' => 'COK',
            'numeric' => '184',
            'currency' => [
                'NZD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Costa Rica',
            'alpha2' => 'CR',
            'alpha3' => 'CRI',
            'numeric' => '188',
            'currency' => [
                'CRC',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Côte d\'Ivoire',
            'alpha2' => 'CI',
            'alpha3' => 'CIV',
            'numeric' => '384',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Croatia',
            'alpha2' => 'HR',
            'alpha3' => 'HRV',
            'numeric' => '191',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Cuba',
            'alpha2' => 'CU',
            'alpha3' => 'CUB',
            'numeric' => '192',
            'currency' => [
                'CUC',
                'CUP',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Curaçao',
            'alpha2' => 'CW',
            'alpha3' => 'CUW',
            'numeric' => '531',
            'currency' => [
                'ANG',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Cyprus',
            'alpha2' => 'CY',
            'alpha3' => 'CYP',
            'numeric' => '196',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Czechia',
            'alpha2' => 'CZ',
            'alpha3' => 'CZE',
            'numeric' => '203',
            'currency' => [
                'CZK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Denmark',
            'alpha2' => 'DK',
            'alpha3' => 'DNK',
            'numeric' => '208',
            'currency' => [
                'DKK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Djibouti',
            'alpha2' => 'DJ',
            'alpha3' => 'DJI',
            'numeric' => '262',
            'currency' => [
                'DJF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Dominica',
            'alpha2' => 'DM',
            'alpha3' => 'DMA',
            'numeric' => '212',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Dominican Republic',
            'alpha2' => 'DO',
            'alpha3' => 'DOM',
            'numeric' => '214',
            'currency' => [
                'DOP',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Ecuador',
            'alpha2' => 'EC',
            'alpha3' => 'ECU',
            'numeric' => '218',
            'currency' => [
                'USD',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Egypt',
            'alpha2' => 'EG',
            'alpha3' => 'EGY',
            'numeric' => '818',
            'currency' => [
                'EGP',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'El Salvador',
            'alpha2' => 'SV',
            'alpha3' => 'SLV',
            'numeric' => '222',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Equatorial Guinea',
            'alpha2' => 'GQ',
            'alpha3' => 'GNQ',
            'numeric' => '226',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Eritrea',
            'alpha2' => 'ER',
            'alpha3' => 'ERI',
            'numeric' => '232',
            'currency' => [
                'ERN',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Estonia',
            'alpha2' => 'EE',
            'alpha3' => 'EST',
            'numeric' => '233',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Ethiopia',
            'alpha2' => 'ET',
            'alpha3' => 'ETH',
            'numeric' => '231',
            'currency' => [
                'ETB',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Eswatini',
            'alpha2' => 'SZ',
            'alpha3' => 'SWZ',
            'numeric' => '748',
            'currency' => [
                'SZL',
                'ZAR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Falkland Islands (Malvinas)',
            'alpha2' => 'FK',
            'alpha3' => 'FLK',
            'numeric' => '238',
            'currency' => [
                'FKP',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Faroe Islands',
            'alpha2' => 'FO',
            'alpha3' => 'FRO',
            'numeric' => '234',
            'currency' => [
                'DKK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Fiji',
            'alpha2' => 'FJ',
            'alpha3' => 'FJI',
            'numeric' => '242',
            'currency' => [
                'FJD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Finland',
            'alpha2' => 'FI',
            'alpha3' => 'FIN',
            'numeric' => '246',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'France',
            'alpha2' => 'FR',
            'alpha3' => 'FRA',
            'numeric' => '250',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'French Guiana',
            'alpha2' => 'GF',
            'alpha3' => 'GUF',
            'numeric' => '254',
            'currency' => [
                'EUR',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'French Polynesia',
            'alpha2' => 'PF',
            'alpha3' => 'PYF',
            'numeric' => '258',
            'currency' => [
                'XPF',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'French Southern Territories',
            'alpha2' => 'TF',
            'alpha3' => 'ATF',
            'numeric' => '260',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Antarctica',
        ],
        [
            'name' => 'Gabon',
            'alpha2' => 'GA',
            'alpha3' => 'GAB',
            'numeric' => '266',
            'currency' => [
                'XAF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Gambia',
            'alpha2' => 'GM',
            'alpha3' => 'GMB',
            'numeric' => '270',
            'currency' => [
                'GMD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Georgia',
            'alpha2' => 'GE',
            'alpha3' => 'GEO',
            'numeric' => '268',
            'currency' => [
                'GEL',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Germany',
            'alpha2' => 'DE',
            'alpha3' => 'DEU',
            'numeric' => '276',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Ghana',
            'alpha2' => 'GH',
            'alpha3' => 'GHA',
            'numeric' => '288',
            'currency' => [
                'GHS',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Gibraltar',
            'alpha2' => 'GI',
            'alpha3' => 'GIB',
            'numeric' => '292',
            'currency' => [
                'GIP',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Greece',
            'alpha2' => 'GR',
            'alpha3' => 'GRC',
            'numeric' => '300',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Greenland',
            'alpha2' => 'GL',
            'alpha3' => 'GRL',
            'numeric' => '304',
            'currency' => [
                'DKK',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Grenada',
            'alpha2' => 'GD',
            'alpha3' => 'GRD',
            'numeric' => '308',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Guadeloupe',
            'alpha2' => 'GP',
            'alpha3' => 'GLP',
            'numeric' => '312',
            'currency' => [
                'EUR',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Guam',
            'alpha2' => 'GU',
            'alpha3' => 'GUM',
            'numeric' => '316',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Guatemala',
            'alpha2' => 'GT',
            'alpha3' => 'GTM',
            'numeric' => '320',
            'currency' => [
                'GTQ',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Guernsey',
            'alpha2' => 'GG',
            'alpha3' => 'GGY',
            'numeric' => '831',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Guinea',
            'alpha2' => 'GN',
            'alpha3' => 'GIN',
            'numeric' => '324',
            'currency' => [
                'GNF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Guinea-Bissau',
            'alpha2' => 'GW',
            'alpha3' => 'GNB',
            'numeric' => '624',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Guyana',
            'alpha2' => 'GY',
            'alpha3' => 'GUY',
            'numeric' => '328',
            'currency' => [
                'GYD',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Haiti',
            'alpha2' => 'HT',
            'alpha3' => 'HTI',
            'numeric' => '332',
            'currency' => [
                'HTG',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Heard Island and McDonald Islands',
            'alpha2' => 'HM',
            'alpha3' => 'HMD',
            'numeric' => '334',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Antarctica',
        ],
        [
            'name' => 'Holy See',
            'alpha2' => 'VA',
            'alpha3' => 'VAT',
            'numeric' => '336',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Honduras',
            'alpha2' => 'HN',
            'alpha3' => 'HND',
            'numeric' => '340',
            'currency' => [
                'HNL',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Hong Kong',
            'alpha2' => 'HK',
            'alpha3' => 'HKG',
            'numeric' => '344',
            'currency' => [
                'HKD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Hungary',
            'alpha2' => 'HU',
            'alpha3' => 'HUN',
            'numeric' => '348',
            'currency' => [
                'HUF',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Iceland',
            'alpha2' => 'IS',
            'alpha3' => 'ISL',
            'numeric' => '352',
            'currency' => [
                'ISK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'India',
            'alpha2' => 'IN',
            'alpha3' => 'IND',
            'numeric' => '356',
            'currency' => [
                'INR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Indonesia',
            'alpha2' => 'ID',
            'alpha3' => 'IDN',
            'numeric' => '360',
            'currency' => [
                'IDR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Iran (Islamic Republic of)',
            'alpha2' => 'IR',
            'alpha3' => 'IRN',
            'numeric' => '364',
            'currency' => [
                'IRR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Iraq',
            'alpha2' => 'IQ',
            'alpha3' => 'IRQ',
            'numeric' => '368',
            'currency' => [
                'IQD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Ireland',
            'alpha2' => 'IE',
            'alpha3' => 'IRL',
            'numeric' => '372',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Isle of Man',
            'alpha2' => 'IM',
            'alpha3' => 'IMN',
            'numeric' => '833',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Israel',
            'alpha2' => 'IL',
            'alpha3' => 'ISR',
            'numeric' => '376',
            'currency' => [
                'ILS',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Italy',
            'alpha2' => 'IT',
            'alpha3' => 'ITA',
            'numeric' => '380',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Jamaica',
            'alpha2' => 'JM',
            'alpha3' => 'JAM',
            'numeric' => '388',
            'currency' => [
                'JMD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Japan',
            'alpha2' => 'JP',
            'alpha3' => 'JPN',
            'numeric' => '392',
            'currency' => [
                'JPY',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Jersey',
            'alpha2' => 'JE',
            'alpha3' => 'JEY',
            'numeric' => '832',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Jordan',
            'alpha2' => 'JO',
            'alpha3' => 'JOR',
            'numeric' => '400',
            'currency' => [
                'JOD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Kazakhstan',
            'alpha2' => 'KZ',
            'alpha3' => 'KAZ',
            'numeric' => '398',
            'currency' => [
                'KZT',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Kenya',
            'alpha2' => 'KE',
            'alpha3' => 'KEN',
            'numeric' => '404',
            'currency' => [
                'KES',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Kiribati',
            'alpha2' => 'KI',
            'alpha3' => 'KIR',
            'numeric' => '296',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Korea (Democratic People\'s Republic of)',
            'alpha2' => 'KP',
            'alpha3' => 'PRK',
            'numeric' => '408',
            'currency' => [
                'KPW',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Korea (Republic of)',
            'alpha2' => 'KR',
            'alpha3' => 'KOR',
            'numeric' => '410',
            'currency' => [
                'KRW',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Kosovo',
            'alpha2' => 'XK',
            'alpha3' => 'XKX',
            'numeric' => '412',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Kuwait',
            'alpha2' => 'KW',
            'alpha3' => 'KWT',
            'numeric' => '414',
            'currency' => [
                'KWD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Kyrgyzstan',
            'alpha2' => 'KG',
            'alpha3' => 'KGZ',
            'numeric' => '417',
            'currency' => [
                'KGS',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Lao People\'s Democratic Republic',
            'alpha2' => 'LA',
            'alpha3' => 'LAO',
            'numeric' => '418',
            'currency' => [
                'LAK',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Latvia',
            'alpha2' => 'LV',
            'alpha3' => 'LVA',
            'numeric' => '428',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Lebanon',
            'alpha2' => 'LB',
            'alpha3' => 'LBN',
            'numeric' => '422',
            'currency' => [
                'LBP',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Lesotho',
            'alpha2' => 'LS',
            'alpha3' => 'LSO',
            'numeric' => '426',
            'currency' => [
                'LSL',
                'ZAR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Liberia',
            'alpha2' => 'LR',
            'alpha3' => 'LBR',
            'numeric' => '430',
            'currency' => [
                'LRD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Libya',
            'alpha2' => 'LY',
            'alpha3' => 'LBY',
            'numeric' => '434',
            'currency' => [
                'LYD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Liechtenstein',
            'alpha2' => 'LI',
            'alpha3' => 'LIE',
            'numeric' => '438',
            'currency' => [
                'CHF',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Lithuania',
            'alpha2' => 'LT',
            'alpha3' => 'LTU',
            'numeric' => '440',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Luxembourg',
            'alpha2' => 'LU',
            'alpha3' => 'LUX',
            'numeric' => '442',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Macao',
            'alpha2' => 'MO',
            'alpha3' => 'MAC',
            'numeric' => '446',
            'currency' => [
                'MOP',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'North Macedonia',
            'alpha2' => 'MK',
            'alpha3' => 'MKD',
            'numeric' => '807',
            'currency' => [
                'MKD',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Madagascar',
            'alpha2' => 'MG',
            'alpha3' => 'MDG',
            'numeric' => '450',
            'currency' => [
                'MGA',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Malawi',
            'alpha2' => 'MW',
            'alpha3' => 'MWI',
            'numeric' => '454',
            'currency' => [
                'MWK',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Malaysia',
            'alpha2' => 'MY',
            'alpha3' => 'MYS',
            'numeric' => '458',
            'currency' => [
                'MYR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Maldives',
            'alpha2' => 'MV',
            'alpha3' => 'MDV',
            'numeric' => '462',
            'currency' => [
                'MVR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Mali',
            'alpha2' => 'ML',
            'alpha3' => 'MLI',
            'numeric' => '466',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Malta',
            'alpha2' => 'MT',
            'alpha3' => 'MLT',
            'numeric' => '470',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Marshall Islands',
            'alpha2' => 'MH',
            'alpha3' => 'MHL',
            'numeric' => '584',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Martinique',
            'alpha2' => 'MQ',
            'alpha3' => 'MTQ',
            'numeric' => '474',
            'currency' => [
                'EUR',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Mauritania',
            'alpha2' => 'MR',
            'alpha3' => 'MRT',
            'numeric' => '478',
            'currency' => [
                'MRO',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Mauritius',
            'alpha2' => 'MU',
            'alpha3' => 'MUS',
            'numeric' => '480',
            'currency' => [
                'MUR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Mayotte',
            'alpha2' => 'YT',
            'alpha3' => 'MYT',
            'numeric' => '175',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Mexico',
            'alpha2' => 'MX',
            'alpha3' => 'MEX',
            'numeric' => '484',
            'currency' => [
                'MXN',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Micronesia (Federated States of)',
            'alpha2' => 'FM',
            'alpha3' => 'FSM',
            'numeric' => '583',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Moldova (Republic of)',
            'alpha2' => 'MD',
            'alpha3' => 'MDA',
            'numeric' => '498',
            'currency' => [
                'MDL',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Monaco',
            'alpha2' => 'MC',
            'alpha3' => 'MCO',
            'numeric' => '492',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Mongolia',
            'alpha2' => 'MN',
            'alpha3' => 'MNG',
            'numeric' => '496',
            'currency' => [
                'MNT',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Montenegro',
            'alpha2' => 'ME',
            'alpha3' => 'MNE',
            'numeric' => '499',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Montserrat',
            'alpha2' => 'MS',
            'alpha3' => 'MSR',
            'numeric' => '500',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Morocco',
            'alpha2' => 'MA',
            'alpha3' => 'MAR',
            'numeric' => '504',
            'currency' => [
                'MAD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Mozambique',
            'alpha2' => 'MZ',
            'alpha3' => 'MOZ',
            'numeric' => '508',
            'currency' => [
                'MZN',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Myanmar',
            'alpha2' => 'MM',
            'alpha3' => 'MMR',
            'numeric' => '104',
            'currency' => [
                'MMK',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Namibia',
            'alpha2' => 'NA',
            'alpha3' => 'NAM',
            'numeric' => '516',
            'currency' => [
                'NAD',
                'ZAR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Nauru',
            'alpha2' => 'NR',
            'alpha3' => 'NRU',
            'numeric' => '520',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Nepal',
            'alpha2' => 'NP',
            'alpha3' => 'NPL',
            'numeric' => '524',
            'currency' => [
                'NPR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Netherlands',
            'alpha2' => 'NL',
            'alpha3' => 'NLD',
            'numeric' => '528',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'New Caledonia',
            'alpha2' => 'NC',
            'alpha3' => 'NCL',
            'numeric' => '540',
            'currency' => [
                'XPF',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'New Zealand',
            'alpha2' => 'NZ',
            'alpha3' => 'NZL',
            'numeric' => '554',
            'currency' => [
                'NZD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Nicaragua',
            'alpha2' => 'NI',
            'alpha3' => 'NIC',
            'numeric' => '558',
            'currency' => [
                'NIO',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Niger',
            'alpha2' => 'NE',
            'alpha3' => 'NER',
            'numeric' => '562',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Nigeria',
            'alpha2' => 'NG',
            'alpha3' => 'NGA',
            'numeric' => '566',
            'currency' => [
                'NGN',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Niue',
            'alpha2' => 'NU',
            'alpha3' => 'NIU',
            'numeric' => '570',
            'currency' => [
                'NZD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Norfolk Island',
            'alpha2' => 'NF',
            'alpha3' => 'NFK',
            'numeric' => '574',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Northern Mariana Islands',
            'alpha2' => 'MP',
            'alpha3' => 'MNP',
            'numeric' => '580',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Norway',
            'alpha2' => 'NO',
            'alpha3' => 'NOR',
            'numeric' => '578',
            'currency' => [
                'NOK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Oman',
            'alpha2' => 'OM',
            'alpha3' => 'OMN',
            'numeric' => '512',
            'currency' => [
                'OMR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Pakistan',
            'alpha2' => 'PK',
            'alpha3' => 'PAK',
            'numeric' => '586',
            'currency' => [
                'PKR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Palau',
            'alpha2' => 'PW',
            'alpha3' => 'PLW',
            'numeric' => '585',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Palestine, State of',
            'alpha2' => 'PS',
            'alpha3' => 'PSE',
            'numeric' => '275',
            'currency' => [
                'ILS',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Panama',
            'alpha2' => 'PA',
            'alpha3' => 'PAN',
            'numeric' => '591',
            'currency' => [
                'PAB',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Papua New Guinea',
            'alpha2' => 'PG',
            'alpha3' => 'PNG',
            'numeric' => '598',
            'currency' => [
                'PGK',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Paraguay',
            'alpha2' => 'PY',
            'alpha3' => 'PRY',
            'numeric' => '600',
            'currency' => [
                'PYG',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Peru',
            'alpha2' => 'PE',
            'alpha3' => 'PER',
            'numeric' => '604',
            'currency' => [
                'PEN',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Philippines',
            'alpha2' => 'PH',
            'alpha3' => 'PHL',
            'numeric' => '608',
            'currency' => [
                'PHP',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Pitcairn',
            'alpha2' => 'PN',
            'alpha3' => 'PCN',
            'numeric' => '612',
            'currency' => [
                'NZD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Poland',
            'alpha2' => 'PL',
            'alpha3' => 'POL',
            'numeric' => '616',
            'currency' => [
                'PLN',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Portugal',
            'alpha2' => 'PT',
            'alpha3' => 'PRT',
            'numeric' => '620',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Puerto Rico',
            'alpha2' => 'PR',
            'alpha3' => 'PRI',
            'numeric' => '630',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Qatar',
            'alpha2' => 'QA',
            'alpha3' => 'QAT',
            'numeric' => '634',
            'currency' => [
                'QAR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Réunion',
            'alpha2' => 'RE',
            'alpha3' => 'REU',
            'numeric' => '638',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Romania',
            'alpha2' => 'RO',
            'alpha3' => 'ROU',
            'numeric' => '642',
            'currency' => [
                'RON',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Russian Federation',
            'alpha2' => 'RU',
            'alpha3' => 'RUS',
            'numeric' => '643',
            'currency' => [
                'RUB',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Rwanda',
            'alpha2' => 'RW',
            'alpha3' => 'RWA',
            'numeric' => '646',
            'currency' => [
                'RWF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Saint Barthélemy',
            'alpha2' => 'BL',
            'alpha3' => 'BLM',
            'numeric' => '652',
            'currency' => [
                'EUR',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Saint Helena, Ascension and Tristan da Cunha',
            'alpha2' => 'SH',
            'alpha3' => 'SHN',
            'numeric' => '654',
            'currency' => [
                'SHP',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Saint Kitts and Nevis',
            'alpha2' => 'KN',
            'alpha3' => 'KNA',
            'numeric' => '659',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Saint Lucia',
            'alpha2' => 'LC',
            'alpha3' => 'LCA',
            'numeric' => '662',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Saint Martin (French part)',
            'alpha2' => 'MF',
            'alpha3' => 'MAF',
            'numeric' => '663',
            'currency' => [
                'EUR',
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Saint Pierre and Miquelon',
            'alpha2' => 'PM',
            'alpha3' => 'SPM',
            'numeric' => '666',
            'currency' => [
                'EUR',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Saint Vincent and the Grenadines',
            'alpha2' => 'VC',
            'alpha3' => 'VCT',
            'numeric' => '670',
            'currency' => [
                'XCD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Samoa',
            'alpha2' => 'WS',
            'alpha3' => 'WSM',
            'numeric' => '882',
            'currency' => [
                'WST',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'San Marino',
            'alpha2' => 'SM',
            'alpha3' => 'SMR',
            'numeric' => '674',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Sao Tome and Principe',
            'alpha2' => 'ST',
            'alpha3' => 'STP',
            'numeric' => '678',
            'currency' => [
                'STD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Saudi Arabia',
            'alpha2' => 'SA',
            'alpha3' => 'SAU',
            'numeric' => '682',
            'currency' => [
                'SAR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Senegal',
            'alpha2' => 'SN',
            'alpha3' => 'SEN',
            'numeric' => '686',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Serbia',
            'alpha2' => 'RS',
            'alpha3' => 'SRB',
            'numeric' => '688',
            'currency' => [
                'RSD',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Seychelles',
            'alpha2' => 'SC',
            'alpha3' => 'SYC',
            'numeric' => '690',
            'currency' => [
                'SCR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Sierra Leone',
            'alpha2' => 'SL',
            'alpha3' => 'SLE',
            'numeric' => '694',
            'currency' => [
                'SLL',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Singapore',
            'alpha2' => 'SG',
            'alpha3' => 'SGP',
            'numeric' => '702',
            'currency' => [
                'SGD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Sint Maarten (Dutch part)',
            'alpha2' => 'SX',
            'alpha3' => 'SXM',
            'numeric' => '534',
            'currency' => [
                'ANG',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Slovakia',
            'alpha2' => 'SK',
            'alpha3' => 'SVK',
            'numeric' => '703',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Slovenia',
            'alpha2' => 'SI',
            'alpha3' => 'SVN',
            'numeric' => '705',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Solomon Islands',
            'alpha2' => 'SB',
            'alpha3' => 'SLB',
            'numeric' => '090',
            'currency' => [
                'SBD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Somalia',
            'alpha2' => 'SO',
            'alpha3' => 'SOM',
            'numeric' => '706',
            'currency' => [
                'SOS',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'South Africa',
            'alpha2' => 'ZA',
            'alpha3' => 'ZAF',
            'numeric' => '710',
            'currency' => [
                'ZAR',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'South Georgia and the South Sandwich Islands',
            'alpha2' => 'GS',
            'alpha3' => 'SGS',
            'numeric' => '239',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Antarctica',
        ],
        [
            'name' => 'South Sudan',
            'alpha2' => 'SS',
            'alpha3' => 'SSD',
            'numeric' => '728',
            'currency' => [
                'SSP',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Spain',
            'alpha2' => 'ES',
            'alpha3' => 'ESP',
            'numeric' => '724',
            'currency' => [
                'EUR',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Sri Lanka',
            'alpha2' => 'LK',
            'alpha3' => 'LKA',
            'numeric' => '144',
            'currency' => [
                'LKR',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Sudan',
            'alpha2' => 'SD',
            'alpha3' => 'SDN',
            'numeric' => '729',
            'currency' => [
                'SDG',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Suriname',
            'alpha2' => 'SR',
            'alpha3' => 'SUR',
            'numeric' => '740',
            'currency' => [
                'SRD',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Svalbard and Jan Mayen',
            'alpha2' => 'SJ',
            'alpha3' => 'SJM',
            'numeric' => '744',
            'currency' => [
                'NOK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Sweden',
            'alpha2' => 'SE',
            'alpha3' => 'SWE',
            'numeric' => '752',
            'currency' => [
                'SEK',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Switzerland',
            'alpha2' => 'CH',
            'alpha3' => 'CHE',
            'numeric' => '756',
            'currency' => [
                'CHF',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'Syrian Arab Republic',
            'alpha2' => 'SY',
            'alpha3' => 'SYR',
            'numeric' => '760',
            'currency' => [
                'SYP',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Taiwan (Province of China)',
            'alpha2' => 'TW',
            'alpha3' => 'TWN',
            'numeric' => '158',
            'currency' => [
                'TWD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Tajikistan',
            'alpha2' => 'TJ',
            'alpha3' => 'TJK',
            'numeric' => '762',
            'currency' => [
                'TJS',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Tanzania, United Republic of',
            'alpha2' => 'TZ',
            'alpha3' => 'TZA',
            'numeric' => '834',
            'currency' => [
                'TZS',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Thailand',
            'alpha2' => 'TH',
            'alpha3' => 'THA',
            'numeric' => '764',
            'currency' => [
                'THB',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Timor-Leste',
            'alpha2' => 'TL',
            'alpha3' => 'TLS',
            'numeric' => '626',
            'currency' => [
                'USD',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Togo',
            'alpha2' => 'TG',
            'alpha3' => 'TGO',
            'numeric' => '768',
            'currency' => [
                'XOF',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Tokelau',
            'alpha2' => 'TK',
            'alpha3' => 'TKL',
            'numeric' => '772',
            'currency' => [
                'NZD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Tonga',
            'alpha2' => 'TO',
            'alpha3' => 'TON',
            'numeric' => '776',
            'currency' => [
                'TOP',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Trinidad and Tobago',
            'alpha2' => 'TT',
            'alpha3' => 'TTO',
            'numeric' => '780',
            'currency' => [
                'TTD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Tunisia',
            'alpha2' => 'TN',
            'alpha3' => 'TUN',
            'numeric' => '788',
            'currency' => [
                'TND',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Turkey',
            'alpha2' => 'TR',
            'alpha3' => 'TUR',
            'numeric' => '792',
            'currency' => [
                'TRY',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Turkmenistan',
            'alpha2' => 'TM',
            'alpha3' => 'TKM',
            'numeric' => '795',
            'currency' => [
                'TMT',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Turks and Caicos Islands',
            'alpha2' => 'TC',
            'alpha3' => 'TCA',
            'numeric' => '796',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Tuvalu',
            'alpha2' => 'TV',
            'alpha3' => 'TUV',
            'numeric' => '798',
            'currency' => [
                'AUD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Uganda',
            'alpha2' => 'UG',
            'alpha3' => 'UGA',
            'numeric' => '800',
            'currency' => [
                'UGX',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Ukraine',
            'alpha2' => 'UA',
            'alpha3' => 'UKR',
            'numeric' => '804',
            'currency' => [
                'UAH',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'United Arab Emirates',
            'alpha2' => 'AE',
            'alpha3' => 'ARE',
            'numeric' => '784',
            'currency' => [
                'AED',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'United Kingdom of Great Britain and Northern Ireland',
            'alpha2' => 'GB',
            'alpha3' => 'GBR',
            'numeric' => '826',
            'currency' => [
                'GBP',
            ],
            'continent' => 'Europe',
        ],
        [
            'name' => 'United States of America',
            'alpha2' => 'US',
            'alpha3' => 'USA',
            'numeric' => '840',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'United States Minor Outlying Islands',
            'alpha2' => 'UM',
            'alpha3' => 'UMI',
            'numeric' => '581',
            'currency' => [
                'USD',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Uruguay',
            'alpha2' => 'UY',
            'alpha3' => 'URY',
            'numeric' => '858',
            'currency' => [
                'UYU',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Uzbekistan',
            'alpha2' => 'UZ',
            'alpha3' => 'UZB',
            'numeric' => '860',
            'currency' => [
                'UZS',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Vanuatu',
            'alpha2' => 'VU',
            'alpha3' => 'VUT',
            'numeric' => '548',
            'currency' => [
                'VUV',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Venezuela (Bolivarian Republic of)',
            'alpha2' => 'VE',
            'alpha3' => 'VEN',
            'numeric' => '862',
            'currency' => [
                'VEF',
            ],
            'continent' => 'South America',
        ],
        [
            'name' => 'Viet Nam',
            'alpha2' => 'VN',
            'alpha3' => 'VNM',
            'numeric' => '704',
            'currency' => [
                'VND',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Virgin Islands (British)',
            'alpha2' => 'VG',
            'alpha3' => 'VGB',
            'numeric' => '092',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Virgin Islands (U.S.)',
            'alpha2' => 'VI',
            'alpha3' => 'VIR',
            'numeric' => '850',
            'currency' => [
                'USD',
            ],
            'continent' => 'North America',
        ],
        [
            'name' => 'Wallis and Futuna',
            'alpha2' => 'WF',
            'alpha3' => 'WLF',
            'numeric' => '876',
            'currency' => [
                'XPF',
            ],
            'continent' => 'Oceania',
        ],
        [
            'name' => 'Western Sahara',
            'alpha2' => 'EH',
            'alpha3' => 'ESH',
            'numeric' => '732',
            'currency' => [
                'MAD',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Yemen',
            'alpha2' => 'YE',
            'alpha3' => 'YEM',
            'numeric' => '887',
            'currency' => [
                'YER',
            ],
            'continent' => 'Asia',
        ],
        [
            'name' => 'Zambia',
            'alpha2' => 'ZM',
            'alpha3' => 'ZMB',
            'numeric' => '894',
            'currency' => [
                'ZMW',
            ],
            'continent' => 'Africa',
        ],
        [
            'name' => 'Zimbabwe',
            'alpha2' => 'ZW',
            'alpha3' => 'ZWE',
            'numeric' => '716',
            'currency' => [
                'BWP',
                'EUR',
                'GBP',
                'USD',
                'ZAR',
            ],
            'continent' => 'Africa',
        ],
    ];
}
