<?php

namespace League\ISO3166\Obj;

class ISO3166Test extends \PHPUnit_Framework_TestCase
{
    /** @var ISO3166 */
    private $iso3166;

    /**
     * Get instance for each test.
     */
    public function setUp()
    {
        $this->iso3166 = new ISO3166();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ISO3166::class, $this->iso3166);
    }

    /**
     * Looked up country data should be wrapped in a Country instance.
     */
    public function testLookup()
    {
        $this->validateCountry($this->iso3166->alpha2('AF'));
    }

    /**
     * Country instance should have the right structure.
     */
    public function testCountryStructure()
    {
        $this->iso3166 = new ISO3166(
            [
                [
                    'name'     => 'Narnia',
                    'alpha2'   => 'NA',
                    'alpha3'   => 'NAR',
                    'numeric'  => '888',
                    'currency' => [
                        'GLD',
                    ],
                ],
            ]
        );

        $narnia = $this->iso3166->alpha2('NA');
        $this->assertEquals('Narnia', $narnia->name);
        $this->assertEquals('NA', $narnia->alpha2);
        $this->assertEquals('NAR', $narnia->alpha3);
        $this->assertEquals('888', $narnia->numeric);
        $this->assertEquals('GLD', $narnia->currency[0]);
    }

    /**
     * Should be able to get all Country instances.
     */
    public function testAll()
    {
        $this->validateIteratorCountries($this->iso3166->all());
    }

    /**
     * Should be able to get an indexed iterator all countries.
     */
    public function testIterator()
    {
        $this->validateIteratorCountries($this->iso3166->iterator('alpha2'));
    }

    /**
     * Should be able to get an iterator for all countries.
     */
    public function testGetIterator()
    {
        $this->validateIteratorCountries($this->iso3166->getIterator());
    }

    /**
     * @param \Iterator|\Generator|array $countries
     */
    private function validateIteratorCountries($countries)
    {
        foreach ($countries as $index => $country) {
            $this->validateCountry($country);
        }
    }

    /**
     * @param Country $country
     */
    private function validateCountry(Country $country)
    {
        $this->assertInstanceOf(Country::class, $country);
        $this->assertNotEmpty($country->name);
        $this->assertNotEmpty($country->alpha2);
        $this->assertNotEmpty($country->alpha3);
        $this->assertNotEmpty($country->numeric);
        $this->assertNotEmpty($country->currency);
    }
}
