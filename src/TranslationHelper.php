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
     * Returns the translation of a TranslatedString using the current language and the fallback languages.
     *
     * @param AbstractTranslatedString $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function get(AbstractTranslatedString $string)
    {
        $context = $this->getCurrentContext();

        return $string->get($context->getLocale(), $context->getFallback());
    }

    /**
     * Set the translation for the current language in a TranslatedString.
     *
     * @param AbstractTranslatedString $string
     * @param string                    $translation
     *
     * @return AbstractTranslatedString Returns $string
     */
    public function set(AbstractTranslatedString $string, $translation)
    {
        $locale = $this->getCurrentContext()->getLocale();

        $string->set($translation, $locale);

        return $string;
    }

    /**
     * Set many translations at once in a TranslatedString.
     *
     * @param AbstractTranslatedString $string
     * @param string[]                  $translations Must be an array of translations, indexed by the language.
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
     * @return TranslationManager
     */
    public function getTranslationManager()
    {
        return $this->translationManager;
    }

    /**
     * @return TranslationContext
     */
    public function getCurrentContext()
    {
        return $this->translationManager->getCurrentContext();
    }
}
