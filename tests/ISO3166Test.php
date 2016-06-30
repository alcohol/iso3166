<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

class ISO3166Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @testdox Calling getByAlpha2 with an invalid alpha2 throws a DomainException.
     * @dataProvider invalidAlpha2Provider
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid alpha2: .*$/
     *
     * @param string $alpha2
     */
    public function testGetByAlpha2Invalid($alpha2)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha2($alpha2);
    }

    /**
     * @testdox Calling getByAlpha2 with an unknown alpha2 throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: ZZ
     */
    public function testGetByAlpha2Unknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha2('ZZ');
    }

    /**
     * @testdox Calling getByAlpha2 with a known alpha2 returns an associative array with the data.
     * @dataProvider alpha2Provider
     *
     * @param string $alpha2
     * @param array $expected
     */
    public function testGetByAlpha2($alpha2, array $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByAlpha2($alpha2));
    }

    /**
     * @testdox Calling getByAlpha3 with an invalid alpha3 throws a DomainException.
     * @dataProvider invalidAlpha3Provider
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid alpha3: .*$/
     *
     * @param string $alpha3
     */
    public function testGetByAlpha3Invalid($alpha3)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha3($alpha3);
    }

    /**
     * @testdox Calling getByAlpha3 with an unknown alpha3 throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: ZZZ
     */
    public function testGetByAlpha3Unknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByAlpha3('ZZZ');
    }

    /**
     * @testdox Calling getByAlpha3 with a known alpha3 returns an associative array with the data.
     * @dataProvider alpha3Provider
     *
     * @param string $alpha3
     * @param array $expected
     */
    public function testGetByAlpha3($alpha3, array $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByAlpha3($alpha3));
    }

    /**
     * @testdox Calling getByNumeric with an invalid numeric throws a DomainException.
     * @dataProvider invalidNumericProvider
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /^Not a valid numeric: .*$/
     *
     * @param string $numeric
     */
    public function testGetByNumericInvalid($numeric)
    {
        $iso3166 = new ISO3166();
        $iso3166->getByNumeric($numeric);
    }

    /**
     * @testdox Calling getByNumeric with an unknown numeric throws a OutOfBoundsException.
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage ISO 3166-1 does not contain: 000
     */
    public function testGetByNumericUnknown()
    {
        $iso3166 = new ISO3166();
        $iso3166->getByNumeric('000');
    }

    /**
     * @testdox Calling getByNumeric with a known numeric returns an associative array with the data.
     * @dataProvider numericProvider
     *
     * @param string $numeric
     * @param array $expected
     */
    public function testGetByNumeric($numeric, $expected)
    {
        $iso3166 = new ISO3166();
        $this->assertEquals($expected, $iso3166->getByNumeric($numeric));
    }

    /**
     * @testdox Calling getAll returns an array with all elements.
     */
    public function testGetAll()
    {
        $iso3166 = new ISO3166();
        $this->assertInternalType('array', $iso3166->getAll());
        $this->assertCount(249, $iso3166->getAll());
    }

    /**
     * @testdox Iterating over $instance should behave as expected.
     */
    public function testIterator()
    {
        $iso3166 = new ISO3166();

        $i = 0;
        foreach ($iso3166 as $key => $value) {
            ++$i;
        }

        $this->assertEquals(count($iso3166->getAll()), $i, 'Compare iterated count to count(getAll()).');
    }

    /**
     * @testdox Iterating over $instance->listBy() should behave as expected.
     */
    public function testListBy()
    {
        $iso3166 = new ISO3166();

        try {
            foreach ($iso3166->listBy('foo') as $key => $value) {
                // void
            }
        } catch (\Exception $e) {
            $this->assertInstanceOf('DomainException', $e);
            $this->assertRegExp('{Invalid value for \$indexBy, got "\w++", expected one of:(?: \w++,?)+}', $e->getMessage());
        } finally {
            $this->assertTrue(isset($e));
        }

        $i = 0;
        foreach ($iso3166->listBy(ISO3166::KEY_ALPHA3) as $key => $value) {
            ++$i;
        }

        $this->assertEquals(count($iso3166->getAll()), $i, 'Compare iterated count to count(getAll()).');
    }

    /**
     * @return array
     */
    public function invalidAlpha2Provider()
    {
        return [['Z'], ['ZZZ'], [1], [123]];
    }

    /**
     * @return array
     */
    public function alpha2Provider()
    {
        return $this->getCountries('alpha2');
    }

    /**
     * @return array
     */
    public function invalidAlpha3Provider()
    {
        return [['ZZ'], ['ZZZZ'], [12], [1234]];
    }

    /**
     * @return array
     */
    public function alpha3Provider()
    {
        return $this->getCountries('alpha3');
    }

    /**
     * @return array
     */
    public function invalidNumericProvider()
    {
        return [['00'], ['0000'], ['ZZ'], ['ZZZZ']];
    }

    /**
     * @return array
     */
    public function numericProvider()
    {
        return $this->getCountries('numeric');
    }

    /**
     * @param string $indexedBy
     *
     * @return array
     */
    private function getCountries($indexedBy)
    {
        $reflected = new \ReflectionClass('League\ISO3166\ISO3166');
        $countries = $reflected->getProperty('countries');
        $countries->setAccessible(true);
        $countries = $countries->getValue(new ISO3166());

        return array_reduce(
            $countries,
            function (array $carry, array $country) use ($indexedBy) {
                $carry[] = [$country[$indexedBy], $country];

                return $carry;
            },
            []
        );
    }
}
