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

class ISO3166Test extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    public $foo = [
        ISO3166::KEY_ALPHA2 => 'FO',
        ISO3166::KEY_ALPHA3 => 'FOO',
        ISO3166::KEY_NUMERIC => '001',
    ];

    /** @var array */
    public $bar = [
        ISO3166::KEY_ALPHA2 => 'BA',
        ISO3166::KEY_ALPHA3 => 'BAR',
        ISO3166::KEY_NUMERIC => '002',
    ];

    /** @var ISO3166 */
    public $iso3166;

    public function setUp()
    {
        $validator = new ISO3166DataValidator();
        $this->iso3166 = new ISO3166($validator->validate([$this->foo, $this->bar]));
    }

    /**
     * @testdox Calling getByAlpha2 with bad input throws various exceptions.
     * @dataProvider invalidAlpha2Provider
     *
     * @param string $alpha2
     * @param string $expectedException
     * @param string $exceptionPattern
     */
    public function testGetByAlpha2Invalid($alpha2, $expectedException, $exceptionPattern)
    {
        $this->setExpectedExceptionRegExp($expectedException, $exceptionPattern);

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
     *
     * @param string $alpha3
     * @param string $expectedException
     * @param string $exceptionPattern
     */
    public function testGetByAlpha3Invalid($alpha3, $expectedException, $exceptionPattern)
    {
        $this->setExpectedExceptionRegExp($expectedException, $exceptionPattern);

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
     *
     * @param string $numeric
     * @param string $expectedException
     * @param string $exceptionPattern
     */
    public function testGetByNumericInvalid($numeric, $expectedException, $exceptionPattern)
    {
        $this->setExpectedExceptionRegExp($expectedException, $exceptionPattern);

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
     * @testdox Calling getAll returns an array with all elements.
     */
    public function testGetAll()
    {
        $this->assertInternalType('array', $this->iso3166->all(), 'getAll() should return an array.');
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
                // void
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
