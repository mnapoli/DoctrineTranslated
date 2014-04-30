<?php

namespace Mnapoli\Translated;

/**
 * Translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface TranslatedString
{
    /**
     * Set the translation of the string for the given language.
     *
     * @param string $translation
     * @param string $language
     *
     * @throws \InvalidArgumentException The given language is unknown
     * @return string
     */
    public function set($translation, $language);

    /**
     * Returns the translation of the string in the given language.
     *
     * @param string $language
     *
     * @throws \InvalidArgumentException The given language is unknown
     * @return string
     */
    public function get($language);
}
