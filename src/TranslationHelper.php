<?php

namespace Mnapoli\Translated;

/**
 * Helper to display and edit translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslationHelper
{
    /**
     * @var TranslationContext
     */
    private $context;

    public function __construct(TranslationContext $context)
    {
        $this->context = $context;
    }

    /**
     * Returns the translation of a TranslatedString using the current locale and the fallback locales.
     *
     * @param TranslatedString $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function toString(TranslatedString $string)
    {
        $value = $string->get($this->context->getLocale());

        if ($value) {
            return $value;
        }

        foreach ($this->context->getFallback() as $fallbackLocale) {
            $value = $string->get($fallbackLocale);
            if ($value) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Set the translation for the current locale in a TranslatedString.
     *
     * @param TranslatedString $string
     * @param string           $translation
     *
     * @return TranslatedString Returns $string
     */
    public function set(TranslatedString $string, $translation)
    {
        $locale = $this->context->getLocale();

        $string->set($translation, $locale);

        return $string;
    }

    /**
     * Set many translations at once in a TranslatedString.
     *
     * @param TranslatedString $string
     * @param string[]         $translations Must be an array of translations, indexed by the locale.
     *
     * @return TranslatedString Returns $string
     */
    public function setMany(TranslatedString $string, array $translations)
    {
        foreach ($translations as $locale => $translation) {
            $string->set($translation, $locale);
        }

        return $string;
    }
}
