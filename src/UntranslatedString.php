<?php

namespace Mnapoli\Translated;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class UntranslatedString implements TranslatedStringInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        $this->value = $string;
    }

    /**
     * {@inheritdoc}
     */
    public function get($language, array $fallbacks = [])
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($translation, $language)
    {
        $this->value = $translation;
    }

    /**
     * {@inheritdoc}
     */
    public function concat($string)
    {
        return StringConcatenation::fromArray(array_merge([$this], func_get_args()));
    }
}
