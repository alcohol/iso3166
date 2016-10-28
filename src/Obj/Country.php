<?php

namespace League\ISO3166\Obj;

/**
 * Value object for ISO3166 country information.
 */
class Country
{
    /** @var string */
    public $name;

    /** @var string */
    public $alpha2;

    /** @var string */
    public $alpha3;

    /** @var string */
    public $numeric;

    /** @var string[] */
    public $currency;

    /**
     * @param string   $name
     * @param string   $alpha2
     * @param string   $alpha3
     * @param string   $numeric
     * @param string[] $currency
     */
    public function __construct(
        $name,
        $alpha2,
        $alpha3,
        $numeric,
        array $currency
    ) {
        $this->name = $name;
        $this->alpha2 = $alpha2;
        $this->alpha3 = $alpha3;
        $this->numeric = $numeric;
        $this->currency = $currency;
    }
}
