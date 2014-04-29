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
     * @param string|null $language
     *
     * @throws \BadMethodCallException   The language must be given if $translation is given.
     * @throws \InvalidArgumentException The given language is unknown
     */
    public function __construct($translation = null, $language = null)
    {
        if ($translation !== null && $language === null) {
            throw new \BadMethodCallException(
                'If you provide a translation, you must provide a language'
            );
        }

        if ($translation !== null && $language !== null) {
            $this->set($translation, $language);
        }
    }

    /**
     * Set the translation of the string for the given language.
     *
     * @param string $translation
     * @param string $language
     *
     * @throws \InvalidArgumentException The given language is unknown
     * @return string
     */
    public function set($translation, $language)
    {
        $language = TranslationUtils::getLanguage($language);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no language "%s" defined', $language));
        }

        $this->{$language} = $translation;
    }

    /**
     * Returns the translation of the string in the given language.
     *
     * @param string $language
     *
     * @throws \InvalidArgumentException The given language is unknown
     * @return string
     */
    public function get($language)
    {
        $language = TranslationUtils::getLanguage($language);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no language "%s" defined', $language));
        }

        return $this->{$language};
    }
}
