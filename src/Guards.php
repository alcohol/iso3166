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

final class Guards
{
    /**
     * Assert that input is not an empty string.
     *
     * @phpstan-param mixed $name
     *
     * @phpstan-assert string $name
     *
     * @throws DomainException if input is an empty string
     */
    public static function guardAgainstInvalidName($name): void
    {
        if (!is_string($name)) {
            throw new DomainException(sprintf('Expected string, got %s', gettype($name)));
        }

        if ('' === trim($name)) {
            throw new DomainException('Expected non-empty-string, got empty-string');
        }
    }

    /**
     * Assert that input looks like an alpha2 key.
     *
     * @phpstan-param mixed $alpha2
     *
     * @phpstan-assert string $alpha2
     *
     * @throws DomainException if input does not look like an alpha2 key
     */
    public static function guardAgainstInvalidAlpha2($alpha2): void
    {
        if (!is_string($alpha2)) {
            throw new DomainException(sprintf('Expected string, got %s', gettype($alpha2)));
        }

        if (1 !== preg_match('/^[a-zA-Z]{2}$/', $alpha2)) {
            throw new DomainException(sprintf('Not a valid alpha2 key: %s', $alpha2));
        }
    }

    /**
     * Assert that input looks like an alpha3 key.
     *
     * @phpstan-param mixed $alpha3
     *
     * @phpstan-assert string $alpha3
     *
     * @throws DomainException if input does not look like an alpha3 key
     */
    public static function guardAgainstInvalidAlpha3($alpha3): void
    {
        if (!is_string($alpha3)) {
            throw new DomainException(sprintf('Expected string, got %s', gettype($alpha3)));
        }

        if (1 !== preg_match('/^[a-zA-Z]{3}$/', $alpha3)) {
            throw new DomainException(sprintf('Not a valid alpha3 key: %s', $alpha3));
        }
    }

    /**
     * Assert that input looks like a numeric key.
     *
     * @phpstan-param mixed $numeric
     *
     * @phpstan-assert string $numeric
     *
     * @throws DomainException if input does not look like a numeric key
     */
    public static function guardAgainstInvalidNumeric($numeric): void
    {
        if (!is_string($numeric)) {
            throw new DomainException(sprintf('Expected string, got %s', gettype($numeric)));
        }

        if (1 !== preg_match('/^\d{3}$/', $numeric)) {
            throw new DomainException(sprintf('Not a valid numeric key: %s', $numeric));
        }
    }
}
