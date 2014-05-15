<?php

namespace Mnapoli\Translated;

/**
 * Manages everything related to the translations.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Translator
{
    /**
     * @var array
     */
    private $fallbacks = [];

    /**
     * @var string
     */
    private $currentLocale;

    /**
     * Example of fallbacks:
     *
     *     [
     *         'fr' => ['en'],
     *         'de' => ['en', 'fr'], // multiple fallbacks are possible
     *     ]
     *
     * @param string $currentLocale The current locale (or provide a default locale if none available).
     * @param array  $fallbacks
     */
    public function __construct($currentLocale, array $fallbacks = [])
    {
        $this->fallbacks = $fallbacks;
        $this->currentLocale = $currentLocale;
    }

    /**
     * @param string $locale
     */
    public function setCurrentLocale($locale)
    {
        $this->currentLocale = $locale;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the translation of a TranslatedString in the current locale, using the configured fallbacks.
     *
     * @param AbstractTranslatedString $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function get(AbstractTranslatedString $string)
    {
        return $string->get($this->currentLocale, $this->getFallbacks($this->currentLocale));
    }

    /**
     * Set the translation for the current locale in a TranslatedString.
     *
     * @param AbstractTranslatedString $string
     * @param string                   $translation
     *
     * @return AbstractTranslatedString Returns $string
     */
    public function set(AbstractTranslatedString $string, $translation)
    {
        $string->set($translation, $this->currentLocale);

        return $string;
    }

    /**
     * Set many translations at once in a TranslatedString.
     *
     * @param AbstractTranslatedString $string
     * @param string[]                 $translations Must be an array of translations, indexed by the language.
     *
     * @return AbstractTranslatedString Returns $string
     */
    public function setMany(AbstractTranslatedString $string, array $translations)
    {
        foreach ($translations as $language => $translation) {
            $string->set($translation, $language);
        }

        return $string;
    }

    /**
     * Returns a list of fallback locales configured for the given locale.
     *
     * @param string $locale
     *
     * @return string[]
     */
    public function getFallbacks($locale)
    {
        if (! array_key_exists($locale, $this->fallbacks)) {
            return [];
        }

        return (array) $this->fallbacks[$locale];
    }
}
