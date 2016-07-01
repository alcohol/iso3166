<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\CountryValidator;

class CountryValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setup()
    {
        $this->validator = new CountryValidator();
    }

    public function teardown()
    {
        $this->validator = null;
    }

    public function testValidateManyNormalizeInput()
    {
        $arr = [
            [
                ISO3166::KEY_ALPHA2 => 'fo',
                ISO3166::KEY_ALPHA3 => 'FoO',
                ISO3166::KEY_NUMERIC => 111,
                'currency' => ['bar'],
                'name' => 'The country of Foo',
            ],
            [
                ISO3166::KEY_ALPHA2 => 'BA',
                ISO3166::KEY_ALPHA3 => 'Bar',
                ISO3166::KEY_NUMERIC => 444,
                'currency' => 'bAz',
                'name' => '     The country of Bar      ',
            ],
        ];
        $res = $this->validator->validateMany($arr);

        $this->assertSame('FO', $res[0][ISO3166::KEY_ALPHA2]);
        $this->assertSame('FOO', $res[0][ISO3166::KEY_ALPHA3]);
        $this->assertSame('111', $res[0][ISO3166::KEY_NUMERIC]);
        $this->assertSame('BAR', $res[1][ISO3166::KEY_ALPHA3]);
        $this->assertSame('444', $res[1][ISO3166::KEY_NUMERIC]);
        $this->assertSame('BAZ', $res[1]['currency']);
        $this->assertSame('The country of Bar', $res[1]['name']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNewInstanceThrowsInvalidArgumentException()
    {
        $this->validator->validateMany([[]]);
    }

    /**
     * @expectedException \DomainException
     */
    public function testNewInstanceThrowsDomainExceptionOnBadCurrency()
    {
        $arr = [
            ISO3166::KEY_ALPHA2 => 'Ba',
            ISO3166::KEY_ALPHA3 => 'BaR',
            ISO3166::KEY_NUMERIC => 444,
            'currency' => '444',
            'name' => 'The country of BAR',
        ];

        $this->validator->validateOne($arr);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNewInstanceThrowsInvalidArgumentExceptionOnBadName()
    {
        $arr = [
            ISO3166::KEY_ALPHA2 => 'Ba',
            ISO3166::KEY_ALPHA3 => 'BaR',
            ISO3166::KEY_NUMERIC => 444,
            'currency' => 'BAZ',
            'name' => [],
        ];

        $this->validator->validateOne($arr);
    }

    /**
     * @expectedException \DomainException
     */
    public function testNewInstanceThrowsDomainExceptionOnBadName()
    {
        $arr = [
            ISO3166::KEY_ALPHA2 => 'BA',
            ISO3166::KEY_ALPHA3 => 'BaR',
            ISO3166::KEY_NUMERIC => 444,
            'currency' => 'BAZ',
            'name' => '',
        ];

        $this->validator->validateOne($arr);
    }
}
