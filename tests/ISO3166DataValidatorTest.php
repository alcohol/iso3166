<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

class ISO3166DataValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ISO3166DataValidator */
    public $validator;

    public function setUp()
    {
        $this->validator = new ISO3166DataValidator();
    }

    /**
     * @testdox Assert that each data entry has the required lookup keys.
     * @dataProvider testcases
     *
     * @param array $data
     * @param string $expectedException
     * @param string $exceptionPattern
     */
    public function testDataEntryHasRequiredKeys(array $data, $expectedException, $exceptionPattern)
    {
        if (false !== $expectedException) {
            $this->setExpectedExceptionRegExp($expectedException, $exceptionPattern);
        }

        $this->assertEquals($data, $this->validator->validate($data));
    }

    /**
     * @return array
     */
    public function testcases()
    {
        return [
            [
                [[ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001']],
                \DomainException::class,
                '{^Each data entry must have a valid alpha2 key.$}',
            ], [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_NUMERIC => '001']],
                \DomainException::class,
                '{^Each data entry must have a valid alpha3 key.$}',
            ], [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO']],
                \DomainException::class,
                '{^Each data entry must have a valid numeric key.$}',
            ], [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001']],
                false,
                false,
            ],
        ];
    }
}
