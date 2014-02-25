<?php

namespace Mnapoli\Translated;

/**
 * Translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class TranslatedString
{
    /**
     * Set the translation of the string for the given locale.
     *
     * @param string $value
     * @param string $locale
     *
     * @throws \InvalidArgumentException The given locale was not defined.
     * @return string
     */
    public function set($value, $locale)
    {
        $language = Util::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        $this->{$language} = $value;
    }

    /**
     * Returns the translation of the string in the given locale.
     *
     * @param string $locale
     *
     * @throws \InvalidArgumentException The given locale was not defined.
     * @return string
     */
    public function get($locale)
    {
        $language = Util::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        return $this->{$language};
    }
}
