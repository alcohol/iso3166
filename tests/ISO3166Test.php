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
use PHPUnit\Framework\TestCase;

class ISO3166Test extends TestCase
{
    /** @var array<string, string> */
    public $foo = [
        ISO3166::KEY_ALPHA2 => 'FO',
        ISO3166::KEY_ALPHA3 => 'FOO',
        ISO3166::KEY_NUMERIC => '001',
        ISO3166::KEY_NAME => 'FOO',
    ];

    /** @var array<string, string> */
    public $bar = [
        ISO3166::KEY_ALPHA2 => 'BA',
        ISO3166::KEY_ALPHA3 => 'BAR',
        ISO3166::KEY_NUMERIC => '002',
        ISO3166::KEY_NAME => 'BAR',
    ];

    /** @var ISO3166 */
    public $iso3166;

    protected function setUp(): void
    {
        $validator = new ISO3166DataValidator();
        $this->iso3166 = new ISO3166($validator->validate([$this->foo, $this->bar]));
    }

    /**
     * @testdox Calling getByAlpha with bad input throws various exceptions.
     *
     * @dataProvider invalidAlphaProvider
     *
     * @phpstan-param class-string<\Throwable> $expectedException
     */
    public function testGetByAlphaInvalid(string $alpha, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->alpha($alpha);
    }

    /**
     * @return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidAlphaProvider(): array
    {
        $invalidNumeric = sprintf('{^Not a valid %s or %s key: .*$}', ISO3166::KEY_ALPHA2, ISO3166::KEY_ALPHA3);
        $noMatch = sprintf('{^No "%s" key found matching: .* and No "%s" key found matching: .*$}', ISO3166::KEY_ALPHA2, ISO3166::KEY_ALPHA3);

        return [
            ['A', DomainException::class, $invalidNumeric],
            ['ABC', OutOfBoundsException::class, $noMatch],
            ['AB', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByAlpha with a known alpha2 returns matching data array.
     */
    public function testGetByAlphaUsingAlpha2(): void
    {
        static::assertEquals($this->foo, $this->iso3166->alpha($this->foo[ISO3166::KEY_ALPHA2]));
        static::assertEquals($this->bar, $this->iso3166->alpha($this->bar[ISO3166::KEY_ALPHA2]));
    }

    /**
     * @testdox Calling getByAlpha with a known alpha3 returns matching data array.
     */
    public function testGetByAlphaUsingAlpha3(): void
    {
        static::assertEquals($this->foo, $this->iso3166->alpha($this->foo[ISO3166::KEY_ALPHA3]));
        static::assertEquals($this->bar, $this->iso3166->alpha($this->bar[ISO3166::KEY_ALPHA3]));
    }

    /**
     * @testdox Calling getByAlpha2 with bad input throws various exceptions.
     *
     * @dataProvider invalidAlpha2Provider
     *
     * @phpstan-param class-string<\Throwable> $expectedException
     */
    public function testGetByAlpha2Invalid(string $alpha2, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->alpha2($alpha2);
    }

    /**
     * @return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidAlpha2Provider(): array
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_ALPHA2);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_ALPHA2);

        return [
            ['A', DomainException::class, $invalidNumeric],
            ['ABC', DomainException::class, $invalidNumeric],
            ['AB', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByAlpha2 with a known alpha2 returns matching data array.
     */
    public function testGetByAlpha2(): void
    {
        static::assertEquals($this->foo, $this->iso3166->alpha2($this->foo[ISO3166::KEY_ALPHA2]));
        static::assertEquals($this->bar, $this->iso3166->alpha2($this->bar[ISO3166::KEY_ALPHA2]));
    }

    /**
     * @testdox Calling getByAlpha3 with bad input throws various exceptions.
     *
     * @dataProvider invalidAlpha3Provider
     *
     * @phpstan-param class-string<\Throwable> $expectedException
     */
    public function testGetByAlpha3Invalid(string $alpha3, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->alpha3($alpha3);
    }

    /**
     * @return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidAlpha3Provider(): array
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_ALPHA3);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_ALPHA3);

        return [
            ['AB', DomainException::class, $invalidNumeric],
            ['ABCD', DomainException::class, $invalidNumeric],
            ['ABC', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByAlpha3 with a known alpha3 returns matching data array.
     */
    public function testGetByAlpha3(): void
    {
        static::assertEquals($this->foo, $this->iso3166->alpha3($this->foo[ISO3166::KEY_ALPHA3]));
        static::assertEquals($this->bar, $this->iso3166->alpha3($this->bar[ISO3166::KEY_ALPHA3]));
    }

    /**
     * @testdox Calling getByNumeric with bad input throws various exceptions.
     *
     * @dataProvider invalidNumericProvider
     *
     * @param class-string<\Throwable> $expectedException
     */
    public function testGetByNumericInvalid(string $numeric, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->numeric($numeric);
    }

    /**
     * @phpstan-return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidNumericProvider(): array
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_NUMERIC);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_NUMERIC);

        return [
            ['00', DomainException::class, $invalidNumeric],
            ['0000', DomainException::class, $invalidNumeric],
            ['AB', DomainException::class, $invalidNumeric],
            ['ABC', DomainException::class, $invalidNumeric],
            ['ABCD', DomainException::class, $invalidNumeric],
            ['000', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByNumeric with a known numeric returns matching data array.
     */
    public function testGetByNumeric(): void
    {
        static::assertEquals($this->foo, $this->iso3166->numeric($this->foo[ISO3166::KEY_NUMERIC]));
        static::assertEquals($this->bar, $this->iso3166->numeric($this->bar[ISO3166::KEY_NUMERIC]));
    }

    /**
     * @testdox Calling getByName with bad input throws various exceptions.
     *
     * @dataProvider invalidNameProvider
     *
     * @param class-string<\Throwable> $expectedException
     */
    public function testGetByNameInvalid(string $name, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->name($name);
    }

    /**
     * @phpstan-return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidNameProvider(): array
    {
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_NAME);

        return [
            ['000', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByName with a known name returns matching data array.
     */
    public function testGetByName(): void
    {
        static::assertEquals($this->foo, $this->iso3166->name($this->foo[ISO3166::KEY_NAME]));
        static::assertEquals($this->bar, $this->iso3166->name($this->bar[ISO3166::KEY_NAME]));
    }

    /**
     * @testdox Calling getByExactName with bad input throws various exceptions.
     *
     * @dataProvider invalidExactNameProvider
     *
     * @param class-string<\Throwable> $expectedException
     */
    public function testGetByExactNameInvalid(string $name, string $expectedException, string $exceptionPattern): void
    {
        static::expectException($expectedException);
        static::expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->exactName($name);
    }

    /**
     * @phpstan-return array<array<string|class-string<\Throwable>|string>>
     */
    public function invalidExactNameProvider(): array
    {
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_NAME);

        return [
            ['FO', OutOfBoundsException::class, $noMatch],
            ['BA', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByExactName with a known name returns matching data array.
     */
    public function testGetByExactName(): void
    {
        static::assertEquals($this->foo, $this->iso3166->exactName($this->foo[ISO3166::KEY_NAME]));
        static::assertEquals($this->bar, $this->iso3166->exactName($this->bar[ISO3166::KEY_NAME]));
    }

    /**
     * @testdox Calling getAll returns an array with all elements.
     */
    public function testGetAll(): void
    {
        static::assertIsArray($this->iso3166->all());
    }

    /**
     * @testdox Iterating over $instance should behave as expected.
     */
    public function testIterator(): void
    {
        $i = 0;
        foreach ($this->iso3166 as $key => $value) {
            ++$i;
        }

        static::assertEquals(\count($this->iso3166->all()), $i, 'Compare iterated count to count(getAll()).');
    }

    /**
     * @testdox Iterating over $instance->listBy() should behave as expected.
     */
    public function testListBy(): void
    {
        try {
            foreach ($this->iso3166->iterator('foo') as $key => $value) {
                static::assertTrue(true);
            }
        } catch (\Exception $e) {
            static::assertInstanceOf(DomainException::class, $e);
            static::assertMatchesRegularExpression('{Invalid value for \$key, got "\w++", expected one of:(?: \w++,?)+}', $e->getMessage());
        } finally {
            static::assertTrue(isset($e));
        }

        $i = 0;
        foreach ($this->iso3166->iterator(ISO3166::KEY_ALPHA3) as $key => $value) {
            ++$i;
        }

        static::assertEquals(\count($this->iso3166), $i, 'Compare iterated count to count($iso3166).');
    }

    /**
     * @testdox Make sure string compare is unicode.
     */
    public function testCountryNameCompare(): void
    {
        $country = (new ISO3166())->name('CÔTE D\'IVOIRE');

        static::assertEquals('CIV', $country['alpha3']);
    }
}
