<?php

namespace Mnapoli\Translated;

/**
 * Translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface TranslatedStringInterface
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

    /**
     * Returns all the translations (even empty ones) as an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Concatenate the string with other strings (translated or not).
     *
     * This method DO NOT modify the current object. It returns a new object.
     *
     * Can take many parameters, for example:
     *
     *     $result = $str->join(' - ', $otherString);
     *
     * @param string|TranslatedStringInterface $string
     * @param string|TranslatedStringInterface ...
     *
     * @return TranslatedStringInterface
     */
    public function concat($string);
}
