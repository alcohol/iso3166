<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace League\ISO3166;

use League\ISO3166\Exception\DomainException;
use PHPUnit\Framework\TestCase;

class ISO3166DataValidatorTest extends TestCase
{
    /** @var ISO3166DataValidator */
    public $validator;

    public function setUp()
    {
        $this->validator = new ISO3166DataValidator();
    }

    /**
     * @testdox Assert that each entry has all the required lookup keys:
     * @dataProvider requiredKeysProvider
     */
    public function testDataEntryHasRequiredKeys(
        array $data,
        string $expectedException = null,
        string $exceptionPattern = null
    ) {
        if (null !== $expectedException && null !== $exceptionPattern) {
            $this->expectException($expectedException);
            $this->expectExceptionMessageRegExp($exceptionPattern);
        }

        $this->assertEquals($data, $this->validator->validate($data));
    }

    /**
     * @return array
     */
    public function requiredKeysProvider()
    {
        return [
            'entry missing alpha2' => [
                [[ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001']],
                DomainException::class,
                '{^Each data entry must have a valid alpha2 key.$}',
            ],
            'entry missing alpha3' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_NUMERIC => '001']],
                DomainException::class,
                '{^Each data entry must have a valid alpha3 key.$}',
            ],
            'entry missing numeric' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO']],
                DomainException::class,
                '{^Each data entry must have a valid numeric key.$}',
            ],
            'entry is complete' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001']],
                null,
                null,
            ],
        ];
    }
}
