<?php

namespace League\ISO3166;

use ArrayIterator;
use Generator;
use League\ISO3166\ISO3166;
use PHPUnit_Framework_TestCase as TestCase;

class DataLocalizerTest extends TestCase
{
    /**
     * @testdox Calling constructor with invalid arguments throws a InvalidArgumentException.
     * @dataProvider constructorThrowsInvalidArgumentExceptionProvider
     * @expectedException \InvalidArgumentException
     */
    public function testNewInstanceThrowsInvalidArgumentException($key, $in_locale)
    {
        new DataLocalizer($key, $in_locale);
    }

    public function constructorThrowsInvalidArgumentExceptionProvider()
    {
        return [
            'invalid $key type' => [
                'key' => [],
                'in_locale' => '',
            ],
            'invalid $in_locale type' => [
                'key' => 'foo',
                'in_locale' => [],
            ],
        ];
    }

    /**
     * @testdox Calling constructor with forbidden key throws a DomainException.
     * @expectedException \DomainException
     */
    public function testNewInstanceThrowsDomainException($key, $in_locale)
    {
        new DataLocalizer(ISO3166::KEY_NUMERIC);
    }

    /**
     * @testdox Calling localize without an iterable throws a InvalidArgumentException.
     * @expectedException \InvalidArgumentException
     */
    public function testLocalizeThrowsInvalidArgumentException()
    {
        (new DataLocalizer())->__invoke('SEN');
    }

    /**
     * @testdox Calling localize with invalid iterable throws a DomainException.
     * @dataProvider invalidLocalizeArgumentsProvider
     * @expectedException \DomainException
     */
    public function testLocalizeThrowsDomainException($input)
    {
        (new DataLocalizer())->__invoke($input);
    }

    public function invalidLocalizeArgumentsProvider()
    {
        return [
            'input type must be an iterable with array as item' => [
                'input' => new ArrayIterator([1, 2, 3]),
            ],
            'item array must contain an alpha3 key' => [
                'input' => [[ISO3166::KEY_ALPHA2 => 'US']],
            ],
            'item array must contain a valid alpha3 key' => [
                'input' => [[ISO3166::KEY_ALPHA3 => 'US']],
            ],
        ];
    }

    public function testLocalizeReturnsGenerator()
    {
        $this->assertInstanceOf(
            Generator::class,
            (new DataLocalizer())->__invoke([[ISO3166::KEY_ALPHA3 => 'BEL']])
        );
    }

    /**
     * @testdox Calling localize
     * @dataProvider localizeProvider
     */
    public function testLocalize($key, $locale, $expected, $data)
    {
        $collection = new ISO3166($data);
        $localizer = new DataLocalizer($key, $locale);
        $firstEntry = array_shift(iterator_to_array($localizer($collection)));
        $this->assertSame($expected, $firstEntry[$key]);
    }

    public function localizeProvider()
    {
        $data =  [(new ISO3166())->getByAlpha2('SN')];
        $unknown_alpha3 = $data;
        $unknown_alpha3[0][ISO3166::KEY_ALPHA3] = 'FOO';
        $default_locale = locale_get_default();
        $local_fr = locale_get_display_region('-SEN', 'fr');
        return [
            'localize adds key' => [
                'key' => 'local_name',
                'locale' => 'fr',
                'expected' => $local_fr,
                'data' => $data,
            ],
            'localize can overrides non required key' => [
                'key' => 'name',
                'locale' => 'fr',
                'expected' => $local_fr,
                'data' => $data,
            ],
            'for unknown alpha3 the localized index takes the alpha3 value' => [
                'key' => 'local_name',
                'locale' => 'fr',
                'expected' => 'FOO',
                'data' => $unknown_alpha3,
            ],
            'for unknown locale, localize uses the default locale' => [
                'key' => 'local_name',
                'locale' => 'zzz',
                'expected' => locale_get_display_region('-SEN', $default_locale),
                'data' => $data,
            ],
        ];
    }
}