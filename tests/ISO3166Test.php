<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\Exception\DomainException;
use League\ISO3166\Exception\InvalidArgumentException;
use League\ISO3166\Exception\OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class ISO3166Test extends TestCase
{
    /** @var array */
    public $foo = [
        ISO3166::KEY_ALPHA2 => 'FO',
        ISO3166::KEY_ALPHA3 => 'FOO',
        ISO3166::KEY_NUMERIC => '001',
        ISO3166::KEY_NAME => 'FOO',
    ];

    /** @var array */
    public $bar = [
        ISO3166::KEY_ALPHA2 => 'BA',
        ISO3166::KEY_ALPHA3 => 'BAR',
        ISO3166::KEY_NUMERIC => '002',
        ISO3166::KEY_NAME => 'BAR',
    ];

    /** @var ISO3166 */
    public $iso3166;

    public function setUp(): void
    {
        $validator = new ISO3166DataValidator();
        $this->iso3166 = new ISO3166($validator->validate([$this->foo, $this->bar]));
    }

    /**
     * @testdox Calling getByAlpha2 with bad input throws various exceptions.
     * @dataProvider invalidAlpha2Provider
     */
    public function testGetByAlpha2Invalid($alpha2, string $expectedException, string $exceptionPattern)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->alpha2($alpha2);
    }

    /**
     * @return array
     */
    public function invalidAlpha2Provider()
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_ALPHA2);
        $expectedString = sprintf('{^Expected \$%s to be of type string, got: .*$}', ISO3166::KEY_ALPHA2);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_ALPHA2);

        return [
            ['A', DomainException::class, $invalidNumeric],
            ['ABC', DomainException::class, $invalidNumeric],
            [1, InvalidArgumentException::class, $expectedString],
            [123, InvalidArgumentException::class, $expectedString],
            ['AB', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByAlpha2 with a known alpha2 returns matching data array.
     */
    public function testGetByAlpha2()
    {
        $this->assertEquals($this->foo, $this->iso3166->alpha2($this->foo[ISO3166::KEY_ALPHA2]));
        $this->assertEquals($this->bar, $this->iso3166->alpha2($this->bar[ISO3166::KEY_ALPHA2]));
    }

    /**
     * @testdox Calling getByAlpha3 with bad input throws various exceptions.
     * @dataProvider invalidAlpha3Provider
     */
    public function testGetByAlpha3Invalid($alpha3, string $expectedException, string $exceptionPattern)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->alpha3($alpha3);
    }

    /**
     * @return array
     */
    public function invalidAlpha3Provider()
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_ALPHA3);
        $expectedString = sprintf('{^Expected \$%s to be of type string, got: .*$}', ISO3166::KEY_ALPHA3);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_ALPHA3);

        return [
            ['AB', DomainException::class, $invalidNumeric],
            ['ABCD', DomainException::class, $invalidNumeric],
            [12, InvalidArgumentException::class, $expectedString],
            [1234, InvalidArgumentException::class, $expectedString],
            ['ABC', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByAlpha3 with a known alpha3 returns matching data array.
     */
    public function testGetByAlpha3()
    {
        $this->assertEquals($this->foo, $this->iso3166->alpha3($this->foo[ISO3166::KEY_ALPHA3]));
        $this->assertEquals($this->bar, $this->iso3166->alpha3($this->bar[ISO3166::KEY_ALPHA3]));
    }

    /**
     * @testdox Calling getByNumeric with bad input throws various exceptions.
     * @dataProvider invalidNumericProvider
     */
    public function testGetByNumericInvalid($numeric, string $expectedException, string $exceptionPattern)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->numeric($numeric);
    }

    /**
     * @return array
     */
    public function invalidNumericProvider()
    {
        $invalidNumeric = sprintf('{^Not a valid %s key: .*$}', ISO3166::KEY_NUMERIC);
        $expectedString = sprintf('{^Expected \$%s to be of type string, got: .*$}', ISO3166::KEY_NUMERIC);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_NUMERIC);

        return [
            ['00', DomainException::class, $invalidNumeric],
            ['0000', DomainException::class, $invalidNumeric],
            ['AB', DomainException::class, $invalidNumeric],
            ['ABC', DomainException::class, $invalidNumeric],
            ['ABCD', DomainException::class, $invalidNumeric],
            [12, InvalidArgumentException::class, $expectedString],
            [123, InvalidArgumentException::class, $expectedString],
            [1234, InvalidArgumentException::class, $expectedString],
            ['000', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByNumeric with a known numeric returns matching data array.
     */
    public function testGetByNumeric()
    {
        $this->assertEquals($this->foo, $this->iso3166->numeric($this->foo[ISO3166::KEY_NUMERIC]));
        $this->assertEquals($this->bar, $this->iso3166->numeric($this->bar[ISO3166::KEY_NUMERIC]));
    }

    /**
     * @testdox Calling getByName with bad input throws various exceptions.
     * @dataProvider invalidNameProvider
     */
    public function testGetByNameInvalid($name, string $expectedException, string $exceptionPattern)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($exceptionPattern);

        $this->iso3166->name($name);
    }

    /**
     * @return array
     */
    public function invalidNameProvider()
    {
        $expectedString = sprintf('{^Expected \$%s to be of type string, got: .*$}', ISO3166::KEY_NAME);
        $noMatch = sprintf('{^No "%s" key found matching: .*$}', ISO3166::KEY_NAME);

        return [
            [12, InvalidArgumentException::class, $expectedString],
            [123, InvalidArgumentException::class, $expectedString],
            [1234, InvalidArgumentException::class, $expectedString],
            ['000', OutOfBoundsException::class, $noMatch],
        ];
    }

    /**
     * @testdox Calling getByName with a known name returns matching data array.
     */
    public function testGetByName()
    {
        $this->assertEquals($this->foo, $this->iso3166->name($this->foo[ISO3166::KEY_NAME]));
        $this->assertEquals($this->bar, $this->iso3166->name($this->bar[ISO3166::KEY_NAME]));
    }

    /**
     * @testdox Calling getAll returns an array with all elements.
     */
    public function testGetAll()
    {
        $this->assertIsArray($this->iso3166->all());
    }

    /**
     * @testdox Iterating over $instance should behave as expected.
     */
    public function testIterator()
    {
        $i = 0;
        foreach ($this->iso3166 as $key => $value) {
            ++$i;
        }

        $this->assertEquals(count($this->iso3166->all()), $i, 'Compare iterated count to count(getAll()).');
    }

    /**
     * @testdox Iterating over $instance->listBy() should behave as expected.
     */
    public function testListBy()
    {
        try {
            foreach ($this->iso3166->iterator('foo') as $key => $value) {
                $this->assertTrue(true);
            }
        } catch (\Exception $e) {
            $this->assertInstanceOf('League\ISO3166\Exception\DomainException', $e);
            $this->assertRegExp('{Invalid value for \$indexBy, got "\w++", expected one of:(?: \w++,?)+}', $e->getMessage());
        } finally {
            $this->assertTrue(isset($e));
        }

        $i = 0;
        foreach ($this->iso3166->iterator(ISO3166::KEY_ALPHA3) as $key => $value) {
            ++$i;
        }

        $this->assertEquals(count($this->iso3166), $i, 'Compare iterated count to count($iso3166).');
    }
}
