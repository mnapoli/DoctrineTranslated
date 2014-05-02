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
     * {@inheritdoc}
     */
    public function concat($string)
    {
        return StringConcatenation::fromArray(array_merge([$this], func_get_args()));
    }
}
