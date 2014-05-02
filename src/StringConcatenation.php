<?php

namespace Mnapoli\Translated;

/**
 * Concatenation of several strings (translated or not).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class StringConcatenation implements TranslatedStringInterface
{
    /**
     * Array of strings to concatenate.
     *
     * @var array
     */
    private $strings = [];

    private $overriddenValues = [];

    /**
     * @param string|TranslatedStringInterface $string
     * @param string|TranslatedStringInterface ...
     *
     * @throws \InvalidArgumentException Parameters must be a string or TranslatedStringInterface
     */
    public function __construct($string)
    {
        foreach (func_get_args() as $string) {
            if (! (is_null($string) || is_string($string) || $string instanceof TranslatedStringInterface)) {
                throw new \InvalidArgumentException(sprintf(
                    'Arguments must be of type string or TranslatedStringInterface, %s given',
                    is_object($string) ? get_class($string) : gettype($string)
                ));
            }

            if ($string instanceof TranslatedStringInterface) {
                $this->strings[] = clone $string;
            } else {
                $this->strings[] = $string;
            }
        }
    }

    /**
     * Creates a new object from an array. Use this method as an alternative to the constructor.
     *
     * @param array $strings Array containing strings or TranslatedStringInterface
     *
     * @return StringConcatenation
     */
    public static function fromArray(array $strings)
    {
        $refl = new \ReflectionClass(get_class());

        return $refl->newInstanceArgs($strings);
    }

    /**
     * {@inheritdoc}
     */
    public function get($language, array $fallbacks = [])
    {
        if (array_key_exists($language, $this->overriddenValues)) {
            return $this->overriddenValues[$language];
        }

        $result = null;

        foreach ($this->strings as $string) {
            if (is_string($string)) {
                $result .= $string;
            } elseif ($string instanceof TranslatedStringInterface) {
                $result .= $string->get($language, $fallbacks);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function set($translation, $language)
    {
        $this->overriddenValues[$language] = $translation;
    }

    /**
     * {@inheritdoc}
     */
    public function concat($string)
    {
        $refl = new \ReflectionClass($this);

        return $refl->newInstanceArgs(array_merge($this->strings, func_get_args()));
    }
}
