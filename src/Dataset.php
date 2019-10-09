<?php

declare(strict_types=1);

namespace League\ISO3166;

use League\ISO3166\Exception\OutOfBoundsException;

interface Dataset extends \Countable, \IteratorAggregate
{
    /**
     * Lookup ISO3166-1 data by given identifier.
     *
     * Looks for a match against the given key for each entry in the dataset.
     *
     * @param string $key
     * @param string $value
     *
     * @throws OutOfBoundsException if key does not exist in dataset
     *
     * @return array
     */
    public function lookup($key, $value);

    /**
     * @return array[]
     */
    public function all();
}