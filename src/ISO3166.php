<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\Exception\DomainException;
use League\ISO3166\Exception\OutOfBoundsException;

final class ISO3166 implements \Countable, \IteratorAggregate, ISO3166DataProvider
{
    /** @var string */
    const KEY_ALPHA2 = 'alpha2';
    /** @var string */
    const KEY_ALPHA3 = 'alpha3';
    /** @var string */
    const KEY_NUMERIC = 'numeric';
    /** @var string */
    const KEY_NAME = 'name';
    /** @var string[] */
    private $keys = [self::KEY_ALPHA2, self::KEY_ALPHA3, self::KEY_NUMERIC, self::KEY_NAME];

    /**
     * @var Dataset
     */
    private $dataset;

    /**
     * @param array[]|Dataset $dataset replace default dataset with the given one
     */
    public function __construct($dataset = null)
    {
        $this->initialize($dataset);
    }

    /**
     * {@inheritdoc}
     */
    public function name($name)
    {
        Guards::guardAgainstInvalidName($name);

        return $this->lookup(self::KEY_NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function alpha2($alpha2)
    {
        Guards::guardAgainstInvalidAlpha2($alpha2);

        return $this->lookup(self::KEY_ALPHA2, $alpha2);
    }

    /**
     * {@inheritdoc}
     */
    public function alpha3($alpha3)
    {
        Guards::guardAgainstInvalidAlpha3($alpha3);

        return $this->lookup(self::KEY_ALPHA3, $alpha3);
    }

    /**
     * {@inheritdoc}
     */
    public function numeric($numeric)
    {
        Guards::guardAgainstInvalidNumeric($numeric);

        return $this->lookup(self::KEY_NUMERIC, $numeric);
    }

    /**
     * @return array[]
     */
    public function all()
    {
        return $this->dataset->all();
    }

    /**
     * @param string $key
     *
     * @throws \League\ISO3166\Exception\DomainException if an invalid key is specified
     *
     * @return \Generator
     */
    public function iterator($key = self::KEY_ALPHA2)
    {
        if (!in_array($key, $this->keys, true)) {
            throw new DomainException(sprintf(
                'Invalid value for $indexBy, got "%s", expected one of: %s',
                $key,
                implode(', ', $this->keys)
            ));
        }

        foreach ($this->dataset as $country) {
            yield $country[$key] => $country;
        }
    }

    /**
     * @see \Countable
     *
     * @internal
     *
     * @return int
     */
    public function count()
    {
        return count($this->dataset);
    }

    /**
     * @see \IteratorAggregate
     *
     * @internal
     *
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->dataset as $country) {
            yield $country;
        }
    }

    /**
     * Lookup ISO3166-1 data by given identifier.
     *
     * Looks for a match against the given key for each entry in the dataset.
     *
     * @param string $key
     * @param string $value
     *
     * @throws \League\ISO3166\Exception\OutOfBoundsException if key does not exist in dataset
     *
     * @return array
     */
    private function lookup($key, $value)
    {
        return $this->dataset->lookup($key, $value);
    }

    private function initialize($dataset)
    {
        if (is_array($dataset)) {
            $this->dataset = new ArrayDataset($dataset);
            return;
        }

        if ($dataset instanceof Dataset) {
            $this->dataset = $dataset;
            return;
        }

        $this->dataset = new DefaultDataset();
    }
}
