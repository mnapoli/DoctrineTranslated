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
     * @param TranslatedStringInterface $string
     *
     * @return null|string Returns the string, or null if no translation was found.
     */
    public function toString(TranslatedStringInterface $string)
    {
        $context = $this->getCurrentContext();

        return $string->get($context->getLocale(), $context->getFallback());
    }

    /**
     * Set the translation for the current language in a TranslatedString.
     *
     * @param TranslatedStringInterface $string
     * @param string                    $translation
     *
     * @return TranslatedStringInterface Returns $string
     */
    public function set(TranslatedStringInterface $string, $translation)
    {
        $locale = $this->getCurrentContext()->getLocale();

        $string->set($translation, $locale);

        return $string;
    }

    /**
     * Set many translations at once in a TranslatedString.
     *
     * @param TranslatedStringInterface $string
     * @param string[]                  $translations Must be an array of translations, indexed by the language.
     *
     * @return TranslatedStringInterface Returns $string
     */
    public function setMany(TranslatedStringInterface $string, array $translations)
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
