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
     * @param string|null $translation Optional. If set, then the language must be given too.
     * @param string|null $locale
     *
     * @throws \BadMethodCallException   The language must be given if $translation is given.
     * @throws \InvalidArgumentException The given locale is unknown
     */
    public function __construct($translation = null, $locale = null)
    {
        if ($translation !== null && $locale !== null) {
            $this->set($translation, $locale);
        }

        if ($translation !== null && $locale === null) {
            throw new \BadMethodCallException(sprintf('There is no locale "%s" defined', $locale));
        }
    }

    /**
     * Set the translation of the string for the given locale.
     *
     * @param string $translation
     * @param string $locale
     *
     * @throws \InvalidArgumentException The given locale is unknown
     * @return string
     */
    public function set($translation, $locale)
    {
        $language = TranslationUtils::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        $this->{$language} = $translation;
    }

    /**
     * Returns the translation of the string in the given locale.
     *
     * @param string $locale
     *
     * @throws \InvalidArgumentException The given locale is unknown
     * @return string
     */
    public function get($locale)
    {
        $language = TranslationUtils::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        return $this->{$language};
    }
}
