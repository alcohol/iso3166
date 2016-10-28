<?php

namespace League\ISO3166\Obj;

/**
 * OOP wrapper for the associative array based ISO3166.
 *
 * @method Country alpha2($alpha2)
 * @method Country alpha3($alpha3)
 * @method Country numeric($numeric)
 */
class ISO3166 extends \League\ISO3166\ISO3166
{
    /**
     * @return Country[]
     */
    public function all()
    {
        return array_map(
            function (array $assoc) {
                return $this->makeCountry($assoc);
            },
            parent::all()
        );
    }

    /**
     * @param string $key
     *
     * @return \Generator
     * @throws \DomainException
     */
    public function iterator($key = self::KEY_ALPHA2)
    {
        foreach (parent::iterator($key) as $index => $assoc) {
            yield $index => $this->makeCountry($assoc);
        }
    }

    /**
     * @return \Generator|Country[]
     */
    public function getIterator()
    {
        foreach (parent::getIterator() as $country) {
            yield $this->makeCountry($country);
        }
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Country
     * @throws \OutOfBoundsException
     */
    protected function lookup($key, $value)
    {
        return $this->makeCountry(parent::lookup($key, $value));
    }

    /**
     * @param array $assoc
     *
     * @return Country
     */
    private function makeCountry(array $assoc)
    {
        return new Country(
            $assoc['name'],
            $assoc['alpha2'],
            $assoc['alpha3'],
            $assoc['numeric'],
            $assoc['currency']
        );
    }
}
