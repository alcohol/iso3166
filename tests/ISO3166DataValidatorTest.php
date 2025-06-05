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
use PHPUnit\Framework\TestCase;

class ISO3166DataValidatorTest extends TestCase
{
    /** @var ISO3166DataValidator */
    public $validator;

    protected function setUp(): void
    {
        $this->validator = new ISO3166DataValidator();
    }

    /**
     * @testdox Assert that each entry has all the required lookup keys:
     *
     * @dataProvider requiredKeysProvider
     *
     * @param array<array<string, string|array<string>>> $data
     *
     * @phpstan-param class-string<\Throwable> $expectedException
     */
    public function testDataEntryHasRequiredKeys(
        array $data,
        string $expectedException = null,
        string $exceptionPattern = null
    ): void {
        if (null !== $expectedException && null !== $exceptionPattern) {
            $this->expectException($expectedException);
            $this->expectExceptionMessageMatches($exceptionPattern);
        }

        static::assertEquals($data, $this->validator->validate($data));
    }

    /**
     * @phpstan-return array<string, array<array<array<string, string>>|class-string<\Throwable>|string|null>>
     */
    public function requiredKeysProvider(): array
    {
        return [
            'entry missing alpha2' => [
                [[ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001', ISO3166::KEY_NAME => 'Foo']],
                DomainException::class,
                '{^Each data entry must have a alpha2 key.$}',
            ],
            'entry missing alpha3' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_NUMERIC => '001', ISO3166::KEY_NAME => 'Foo']],
                DomainException::class,
                '{^Each data entry must have a alpha3 key.$}',
            ],
            'entry missing numeric' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NAME => 'Foo']],
                DomainException::class,
                '{^Each data entry must have a numeric key.$}',
            ],
            'entry missing name' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001']],
                DomainException::class,
                '{^Each data entry must have a name key.$}',
            ],
            'entry is complete' => [
                [[ISO3166::KEY_ALPHA2 => 'FO', ISO3166::KEY_ALPHA3 => 'FOO', ISO3166::KEY_NUMERIC => '001', ISO3166::KEY_NAME => 'Foo']],
                null,
                null,
            ],
        ];
    }
}
