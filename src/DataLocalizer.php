<?php

namespace League\ISO3166;

use DomainException;
use Generator;
use InvalidArgumentException;
use Traversable;

class DataLocalizer
{
    use KeyValidators;

    /**
     *  the index key to add to the entry
     *  @var string
     */
    private $key;

    /**
     *  locale to use to display the region name
     *  @var string
     */
    private $locale;

    /**
     * New Instance
     * 
     * @param string $key    the index key to add to the entry
     * @param string $locale locale to use to display the region name
     */
    public function __construct($key = 'name', $locale = '')
    {
        $this->guardAgainstInvalidKey($key);
        $this->guardAgainstInvalidString($locale, '$locale');
        $this->key = $key;
        $this->locale = $this->filterLocale($locale);
    }

    /**
     * Filters the key to ensure it is a valid key.
     *
     * @param string $input the index key to add to the entry
     *
     * @throws DomainException if input is not valid.
     */
    private function guardAgainstInvalidKey($input) 
    {
        $this->guardAgainstInvalidString($input, '$key');
        $invalid_keys = [ISO3166::KEY_ALPHA3 => 1, ISO3166::KEY_ALPHA2 => 1, ISO3166::KEY_NUMERIC = 1];
        if (isset($invalid_keys[$input])) {
            throw new DomainException(sprintf(
                'Invalid value for $key, got "%s", $key can not be : %s',
                $input,
                implode(', ', array_keys($invalid_keys))
            ));
        }
    }

    /**
     * Filters the key to ensure it is a valid key.
     *
     * @param string $input the index key to add to the entry
     *
     * @return string
     */
    private function filterLocale($locale)
    {
        $locale = trim($locale);
        if ('' !== $locale) {
            return $locale;
        }

        return locale_get_default();
    }

    /**
     * localize a collection of country entries
     *
     * @see DataLocalizer::localize
     *
     * @param iterable $iterable
     *
     * @throws InvalidArgumentException if input is not a iterable.
     *
     * @return Generator
     */
    public function __invoke($iterable)
    {
        return $this->localize($iterable);
    }

    /**
     * localize a collection of country entries
     *
     * @param iterable $iterable
     *
     * @throws InvalidArgumentException if input is not a iterable.
     *
     * @return Generator
     */
    public function localize($iterable)
    {
        if (!is_array($iterable) && !$iterable instanceof Traversable) {
            throw new InvalidArgumentException(sprintf('Expected an iterable got: %s', gettype($iterable)));
        }

        foreach ($iterable as $key => $entry) {
            yield $key => $this->localizeEntry($entry);
        }
    }

    /**
     * Add a key with the country localized name using PHP's intl extension
     *
     * - If the country alpha3 value is unknown the returned entry value will be egal to the alpha3 value
     * - If the locale is empty or unknown the added entry value will be localized according to the locale return
     *   by PHP intl extension function locale_get_default
     *
     * @param array $entry
     *
     * @throws DomainException if entry does not contain a valid ISO 3316-1 alpha3 key.
     *
     * @return array
     */
    private function localizeEntry(array $entry)
    {
        if (!isset($entry[ISO3166::KEY_ALPHA3])) {
            throw new DomainException('Each data entry must contain a valid alpha3 key.');
        }

        $this->guardAgainstInvalidAlpha3($entry[ISO3166::KEY_ALPHA3]);

        $entry[$this->key] = locale_get_display_region(
            '-'.$entry[ISO3166::KEY_ALPHA3],
            $this->locale
        );

        return $entry;
    }
}
