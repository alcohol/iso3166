<?php

namespace League\ISO3166;

use ArrayIterator;
use Generator;
use League\ISO3166\ISO3166;
use PHPUnit_Framework_TestCase as TestCase;

class IntlLocalizerTest extends TestCase
{
    /**
     * @testdox Calling constructor with invalid arguments throws a InvalidArgumentException.
     * @dataProvider constructorThrowsInvalidArgumentExceptionProvider
     * @expectedException \InvalidArgumentException
     */
    public function testNewInstanceThrowsInvalidArgumentException($key, $locale)
    {
        new IntlLocalizer($key, $locale);
    }

    public function constructorThrowsInvalidArgumentExceptionProvider()
    {
        return [
            'invalid $key type' => [
                'key' => [],
                'locale' => '',
            ],
            'invalid $locale type' => [
                'key' => 'foo',
                'locale' => [],
            ],
        ];
    }

    public function testImplementsLocalizeData()
    {
        $this->assertInstanceOf(LocalizeData::class, new IntlLocalizer);
    }

    /**
     * @testdox Calling constructor with forbidden key throws a DomainException.
     * @expectedException \DomainException
     */
    public function testNewInstanceThrowsDomainException()
    {
        new IntlLocalizer(ISO3166::KEY_NUMERIC);
    }

    /**
     * @testdox Calling localize without an iterable throws a InvalidArgumentException.
     * @expectedException \InvalidArgumentException
     */
    public function testLocalizeThrowsInvalidArgumentException()
    {
        iterator_to_array((new IntlLocalizer())->__invoke('SEN'));
    }

    /**
     * @testdox Calling localize with invalid iterable throws a DomainException.
     * @expectedException \DomainException
     */
    public function testLocalizeThrowsDomainException()
    {
        iterator_to_array((new IntlLocalizer())->__invoke([
            [
                ISO3166::KEY_ALPHA2 => 'US',
                ISO3166::KEY_ALPHA3 => 'US',
                ISO3166::KEY_NUMERIC => '686'
            ]
        ]));
    }

    public function testLocalizeReturnsGenerator()
    {
        $this->assertInstanceOf(
            Generator::class,
            (new IntlLocalizer())->__invoke([[ISO3166::KEY_ALPHA3 => 'BEL']])
        );
    }

    /**
     * @testdox Calling localize
     * @dataProvider localizeProvider
     */
    public function testLocalize($key, $locale, $expected, $data)
    {
        $collection = new ISO3166($data);
        $localizer = new IntlLocalizer($key, $locale);
        $res = iterator_to_array($localizer($collection));
        $firstEntry = array_shift($res);

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