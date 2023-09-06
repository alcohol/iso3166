<?php

declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\ISO3166DataProvider;

class ISO3166WithAliases implements ISO3166DataProvider
{
    /** @var ISO3166DataProvider */
    private $source;

    public function __construct(ISO3166DataProvider $iso3166)
    {
        $this->source = $iso3166;
    }

    public function name(string $name): array
    {
        $aliases = [
            'Bolivia' => 'Bolivia (Plurinational State of)',
            'Bolivia, Plurinational State of' => 'Bolivia (Plurinational State of)',
            'Congo-Kinshasa' => 'Congo (Democratic Republic of the)',
            'Congo, Democratic Republic of the' => 'Congo (Democratic Republic of the)',
            'Czech Republic' => 'Czechia',
            'Iran' => 'Iran (Islamic Republic of)',
            'North Korea' => 'Korea (Democratic People\'s Republic of)',
            'South Korea' => 'Korea (Republic of)',
            'Laos' => 'Lao People\'s Democratic Republic',
            'Micronesia' => 'Micronesia (Federated States of)',
            'Moldova' => 'Moldova (Republic of)',
            'Palestine' => 'Palestine, State of',
            'Russia' => 'Russian Federation',
            'Saint Martin' => 'Saint Martin (French part)',
            'Sint Maarten' => 'Sint Maarten (Dutch part)',
            'Taiwan' => 'Taiwan (Province of China)',
            'Tanzania' => 'Tanzania, United Republic of',
            'United Kingdom' => 'United Kingdom of Great Britain and Northern Ireland',
            'United States' => 'United States of America',
            'Venezuela' => 'Venezuela (Bolivarian Republic of)',
            'Vietnam' => 'Viet Nam',
        ];

        foreach ($aliases as $alias => $full) {
            if (0 === strcasecmp($alias, $name)) {
                $name = $full;
                break;
            }
        }

        return $this->source->name($name);
    }

    public function alpha2(string $alpha2): array
    {
        return $this->source->alpha2($alpha2);
    }

    public function alpha3(string $alpha3): array
    {
        return $this->source->alpha3($alpha3);
    }

    public function numeric(string $numeric): array
    {
        return $this->source->numeric($numeric);
    }
}
