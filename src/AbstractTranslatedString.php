<?php

namespace Mnapoli\Translated;

/**
 * Translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class AbstractTranslatedString
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

        $this->{$language} = (string) $translation;
    }

    /**
     * Returns the translation of the string in the given language.
     *
     * @param string   $language
     * @param string[] $fallbacks Languages to use as fallback if the translation is empty.
     *
     * @throws \InvalidArgumentException The given language is unknown
     * @return string
     */
    public function get($language, array $fallbacks = [])
    {
        $language = TranslationUtils::getLanguage($language);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no language "%s" defined', $language));
        }

        $value = $this->{$language};

        if ($value) {
            return $value;
        }

        if (count($fallbacks) > 0) {
            $fallbackLanguage = array_shift($fallbacks);
            return $this->get($fallbackLanguage, $fallbacks);
        }

        return null;
    }

    /**
     * Returns all the translations as an array.
     *
     * @return string[] Array of translations, indexed by the languages.
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    /**
     * Returns all the languages in which the string can be translated.
     *
     * @return string[]
     */
    public function getLanguages()
    {
        return array_keys(get_object_vars($this));
    }

    /**
     * Concatenate the string with other strings (translated or not).
     *
     * This method DO NOT modify the current object. It returns a new object.
     *
     * Can take many parameters, for example:
     *
     *     $result = $str->join(' - ', $otherString);
     *
     * @param string|AbstractTranslatedString $string
     * @param string|AbstractTranslatedString ...
     *
     * @return AbstractTranslatedString
     */
    public function concat($string)
    {
        return static::join(array_merge([$this], func_get_args()));
    }

    /**
     * Create a translated string from an array of translations.
     *
     * Example:
     *
     *     $string = TranslatedString::fromArray(
     *         'en' => 'Hello',
     *         'fr' => 'Bonjour',
     *     )
     *
     * @param array $translations
     *
     * @return AbstractTranslatedString
     */
    public static function fromArray(array $translations)
    {
        /** @var AbstractTranslatedString $result */
        $result = new static();

        foreach ($translations as $lang => $translation) {
            $result->set($translation, $lang);
        }

        return $result;
    }

    /**
     * Joins strings into a single one.
     *
     * @param array $strings Array containing strings or AbstractTranslatedString
     *
     * @throws \InvalidArgumentException The array must contain null, string or AbstractTranslatedString
     *
     * @return AbstractTranslatedString
     */
    public static function join(array $strings)
    {
        /** @var AbstractTranslatedString $result */
        $result = new static();

        foreach ($result->getLanguages() as $language) {
            $s = '';
            foreach ($strings as $string) {
                if (! (is_null($string) || is_string($string) || $string instanceof AbstractTranslatedString)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Arguments must be of type string or AbstractTranslatedString, %s given',
                        is_object($string) ? get_class($string) : gettype($string)
                    ));
                }

                if ($string instanceof AbstractTranslatedString) {
                    $s .= $string->get($language);
                } else {
                    $s .= $string;
                }
            }
            $result->set($s, $language);
        }

        return $result;
    }

    /**
     * Creates a new object from an array of strings, with the same logic as implode().
     *
     * @param string|AbstractTranslatedString $glue    String to use to join the strings that are in the array.
     * @param array                            $strings Array containing strings or AbstractTranslatedString
     *
     * @return AbstractTranslatedString
     */
    public static function implode($glue, array $strings)
    {
        $parameters = [];

        foreach ($strings as $string) {
            $parameters[] = $string;
            $parameters[] = $glue;
        }

        // Remove the last useless glue appearance
        array_pop($parameters);

        return static::join($parameters);
    }

    /**
     * Creates an untranslated string, i.e. a string that has the same value for each language.
     *
     * @param string $string
     *
     * @return AbstractTranslatedString
     */
    public static function untranslated($string)
    {
        /** @var AbstractTranslatedString $result */
        $result = new static();

        foreach ($result->getLanguages() as $language) {
            $result->set($string, $language);
        }

        return $result;
    }
}
