<?php

namespace Mnapoli\Translated;

/**
 * Handles and translates TranslatedString objects.
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
    private $currentLanguage;

    /**
     * Example of fallbacks:
     *
     *     [
     *         'fr' => ['en'],
     *         'de' => ['en', 'fr'], // multiple fallbacks are possible
     *     ]
     *
     * @param string $locale The current locale/language (or provide a default locale if none available).
     * @param array  $fallbacks
     */
    public function __construct($locale, array $fallbacks = [])
    {
        $this->fallbacks = $fallbacks;
        $this->setLanguage($locale);
    }

    /**
     * Sets the current language.
     *
     * @param string $locale Language or locale ('en' or 'en_US' for example)
     */
    public function setLanguage($locale)
    {
        $this->currentLanguage = TranslationUtils::getLanguage($locale);
    }

    /**
     * Returns the current language.
     *
     * Will not return a locale (e.g. 'en_US'), will return a language (e.g. 'en').
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->currentLanguage;
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
        return $string->get($this->currentLanguage, $this->getFallbacks($this->currentLanguage));
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
        $string->set($translation, $this->currentLanguage);

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
     * Returns a list of fallback languages configured for the given language.
     *
     * @param string $language
     *
     * @return string[]
     */
    public function getFallbacks($language)
    {
        if (! array_key_exists($language, $this->fallbacks)) {
            return [];
        }

        return (array) $this->fallbacks[$language];
    }
}
