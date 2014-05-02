<?php

namespace Mnapoli\Translated;

/**
 * Translated string trait to help you implement your translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
trait TranslatedStringTrait
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function get($language)
    {
        $language = TranslationUtils::getLanguage($language);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no language "%s" defined', $language));
        }

        return $this->{$language};
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritdoc}
     */
    public function concat($string)
    {
        $strings = func_get_args();

        $result = new self();

        foreach (get_object_vars($this) as $language => $value) {
            foreach ($strings as $string) {
                if (is_null($string)) {
                    continue;
                } elseif (is_string($string)) {
                    $value .= $string;
                } elseif ($string instanceof TranslatedStringInterface) {
                    $value .= $string->get($language);
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        'Arguments for method "join" must be of type string or TranslatedStringInterface, %s given',
                        is_object($string) ? get_class($string) : gettype($string)
                    ));
                }
            }

            $result->set($value, $language);
        }

        return $result;
    }
}
