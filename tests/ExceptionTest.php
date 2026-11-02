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
use League\ISO3166\Exception\ISO3166Exception;
use League\ISO3166\Exception\OutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @dataProvider exceptionProvider
     *
     * @param class-string<\Throwable> $class
     * @param class-string<\Throwable> $spl
     */
    #[TestDox('Every exception implements the marker interface, the deprecated interface and its SPL parent.')]
    #[DataProvider('exceptionProvider')]
    public function testExceptionHierarchy(callable $call, string $class, string $spl): void
    {
        try {
            $call(new ISO3166());
            static::fail('Expected an exception to be thrown.');
        } catch (Exception $e) {
            static::assertInstanceOf($class, $e);
            static::assertInstanceOf($spl, $e);
            /** @phpstan-ignore classConstant.deprecatedInterface */
            static::assertInstanceOf(ISO3166Exception::class, $e);
        }
    }

    /**
     * @return array<string, array{callable(ISO3166): mixed, class-string<\Throwable>, class-string<\Throwable>}>
     */
    public static function exceptionProvider(): array
    {
        return [
            'invalid alpha2' => [static fn (ISO3166 $iso3166) => $iso3166->alpha2('A'), DomainException::class, \DomainException::class],
            'unknown alpha2' => [static fn (ISO3166 $iso3166) => $iso3166->alpha2('ZZ'), OutOfBoundsException::class, \OutOfBoundsException::class],
        ];
    }
}
