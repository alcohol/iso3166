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

final class ISO3166DataValidator
{
    /**
     * @param array<array<string, mixed>> $data
     *
     * @return array<array<string, mixed>>
     */
    public function validate(array $data): array
    {
        foreach ($data as $entry) {
            $this->assertEntryHasRequiredKeys($entry);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $entry
     *
     * @throws DomainException if given data entry does not have all the required keys
     */
    private function assertEntryHasRequiredKeys(array $entry): void
    {
        if (!isset($entry[ISO3166::KEY_NAME])) {
            throw new DomainException('Each data entry must have a name key.');
        }

        if (false === is_string($entry[ISO3166::KEY_NAME])) {
            throw new DomainException('Property '.ISO3166::KEY_NAME.' of all data entries must be a string.');
        }

        Guards::guardAgainstInvalidName($entry[ISO3166::KEY_NAME]);

        if (!isset($entry[ISO3166::KEY_ALPHA2])) {
            throw new DomainException('Each data entry must have a alpha2 key.');
        }

        if (false === is_string($entry[ISO3166::KEY_ALPHA2])) {
            throw new DomainException('Property '.ISO3166::KEY_ALPHA2.' of all data entries must be a string.');
        }

        Guards::guardAgainstInvalidAlpha2($entry[ISO3166::KEY_ALPHA2]);

        if (!isset($entry[ISO3166::KEY_ALPHA3])) {
            throw new DomainException('Each data entry must have a alpha3 key.');
        }

        if (false === is_string($entry[ISO3166::KEY_ALPHA3])) {
            throw new DomainException('Property '.ISO3166::KEY_ALPHA3.' of all data entries must be a string.');
        }

        Guards::guardAgainstInvalidAlpha3($entry[ISO3166::KEY_ALPHA3]);

        if (!isset($entry[ISO3166::KEY_NUMERIC])) {
            throw new DomainException('Each data entry must have a numeric key.');
        }

        if (false === is_string($entry[ISO3166::KEY_NUMERIC])) {
            throw new DomainException('Property '.ISO3166::KEY_NUMERIC.' of all data entries must be a string.');
        }

        Guards::guardAgainstInvalidNumeric($entry[ISO3166::KEY_NUMERIC]);
    }
}
