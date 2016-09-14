<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

interface ISO3166DataProvider
{
    /**
     * Lookup ISO3166-1 data by alpha2 identifier.
     *
     * @api
     *
     * @param string $alpha2
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like an alpha2 key.
     * @throws \OutOfBoundsException if input does not exist in dataset.
     *
     * @return array
     */
    public function alpha2($alpha2);

    /**
     * Lookup ISO3166-1 data by alpha3 identifier.
     *
     * @api
     *
     * @param string $alpha3
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like an alpha3 key.
     * @throws \OutOfBoundsException if input does not exist in dataset.
     *
     * @return array
     */
    public function alpha3($alpha3);

    /**
     * Lookup ISO3166-1 data by numeric identifier (numerical string, that is).
     *
     * @api
     *
     * @param string $numeric
     *
     * @throws \InvalidArgumentException if input is not a string.
     * @throws \DomainException if input does not look like a numeric key.
     * @throws \OutOfBoundsException if input does not exist in dataset.
     *
     * @return array
     */
    public function numeric($numeric);
}
