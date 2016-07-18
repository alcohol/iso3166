<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

class DataValidator
{
    use KeyValidators;

    /**
     * @param array $data
     *
     * @return array
     */
    public function validate(array $data)
    {
        foreach ($data as $entry) {
            $this->assertEntryHasRequiredKeys($entry);
        }

        return $data;
    }
}
