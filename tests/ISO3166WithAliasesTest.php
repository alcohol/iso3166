<?php

declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\Exception\DomainException;
use League\ISO3166\Exception\OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class ISO3166WithAliasesTest extends TestCase
{
    /** @var ISO3166WithAliases */
    public $iso3166;

    protected function setUp(): void
    {
        $this->iso3166 = new ISO3166WithAliases(new ISO3166);
    }

    public function testAlias(): void
    {
        $this->assertEquals($this->iso3166->name('United States')['name'], 'United States of America');
        $this->assertEquals($this->iso3166->alpha2('US')['alpha2'], 'US');
        $this->assertEquals($this->iso3166->alpha3('USA')['alpha3'], 'USA');
        $this->assertEquals($this->iso3166->numeric('840')['numeric'], '840');
    }
}
