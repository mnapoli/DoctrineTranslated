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
     * @var TranslationContext|null
     */
    private $currentContext;

    /**
     * Example of fallbacks:
     *
     *     [
     *         'en' => ['de', 'fr'],
     *         'fr' => ['en'],
     *         'de' => ['en'],
     *     ]
     *
     * @param string $defaultLocale The default locale, to create the default context.
     * @param array  $fallbacks
     */
    public function __construct($defaultLocale, array $fallbacks = [])
    {
        $this->fallbacks = $fallbacks;

        $this->setCurrentContext($defaultLocale);
    }

    /**
     * Creates a new TranslationContext based on the given locale and sets it
     * as the current context.
     *
     * @param string $locale
     *
     * @return TranslationContext
     */
    public function setCurrentContext($locale)
    {
        $fallback = $this->getFallback($locale);

        $this->currentContext = new TranslationContext($locale, $fallback);

        return $this->currentContext;
    }

    /**
     * @return TranslationContext
     */
    public function getCurrentContext()
    {
        return $this->currentContext;
    }

    /**
     * Returns the translation of a TranslatedString using the current language and the fallback languages.
     *
     * @param AbstractTranslatedString $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function get(AbstractTranslatedString $string)
    {
        $context = $this->currentContext;

        return $string->get($context->getLocale(), $context->getFallback());
    }

    /**
     * Set the translation for the current language in a TranslatedString.
     *
     * @param AbstractTranslatedString $string
     * @param string                   $translation
     *
     * @return AbstractTranslatedString Returns $string
     */
    public function set(AbstractTranslatedString $string, $translation)
    {
        $locale = $this->currentContext->getLocale();

        $string->set($translation, $locale);

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
    protected function getFallback($locale)
    {
        if (! array_key_exists($locale, $this->fallbacks)) {
            return [];
        }

        return (array) $this->fallbacks[$locale];
    }
}
