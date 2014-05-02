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
     * @var TranslationManager
     */
    private $translationManager;

    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    /**
     * Returns the translation of a TranslatedString using the current locale and the fallback locales.
     *
     * @param TranslatedStringInterface $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function toString(TranslatedStringInterface $string)
    {
        $context = $this->translationManager->getCurrentContext();

        return $string->get($context->getLocale(), $context->getFallback());
    }

    /**
     * Set the translation for the current locale in a TranslatedString.
     *
     * @param TranslatedStringInterface $string
     * @param string           $translation
     *
     * @return TranslatedStringInterface Returns $string
     */
    public function set(TranslatedStringInterface $string, $translation)
    {
        $context = $this->translationManager->getCurrentContext();

        $locale = $context->getLocale();

        $string->set($translation, $locale);

        return $string;
    }

    /**
     * Set many translations at once in a TranslatedString.
     *
     * @param TranslatedStringInterface $string
     * @param string[]         $translations Must be an array of translations, indexed by the locale.
     *
     * @return TranslatedStringInterface Returns $string
     */
    public function setMany(TranslatedStringInterface $string, array $translations)
    {
        foreach ($translations as $locale => $translation) {
            $string->set($translation, $locale);
        }

        return $string;
    }
}
