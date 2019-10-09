<?php

declare(strict_types=1);

namespace League\ISO3166;

use League\ISO3166\Exception\OutOfBoundsException;
use Traversable;

class ArrayDataset implements Dataset
{
    /**
     * @var array
     */
    private $countries;

    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }

    public function lookup($key, $value)
    {
        foreach ($this->countries as $country) {
            if (0 === strcasecmp($value, $country[$key])) {
                return $country;
            }
        }

        throw new OutOfBoundsException(
            sprintf('No "%s" key found matching: %s', $key, $value)
        );
    }

    public function all()
    {
        return $this->countries;
    }

    public function getIterator()
    {
        foreach ($this->countries as $country) {
            yield $country;
        }
    }

    public function count()
    {
        return count($this->countries);
    }
}