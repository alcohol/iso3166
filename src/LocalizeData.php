<?php

namespace League\ISO3166;

use DomainException;
use Generator;
use InvalidArgumentException;

interface LocalizeData
{
    /**
     * localize a collection of country entries
     *
     * @param iterable $iterable
     *
     * @throws InvalidArgumentException if $iterable is not a iterable.
     * @throws DomainException if a required entry key is missing.
     *
     * @return Generator
     */
    public function __invoke($iterable);
}
