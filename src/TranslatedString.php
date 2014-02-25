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
     * @var TranslationContext
     */
    private $context;

    public function __construct(TranslationContext $context)
    {
        $this->context = $context;
    }

    public function __toString()
    {
        return $this->get();
    }

    /**
     * Set the translation of the string for the given locale.
     *
     * If no locale is given, then the value will be set for the locale of the current context.
     *
     * @param string      $value
     * @param string|null $locale
     *
     * @throws \InvalidArgumentException The given locale was not defined.
     * @return string
     */
    public function set($value, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->context->getLocale();
        }

        $language = Util::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        $this->{$language} = $value;
    }

    /**
     * Returns the translation of the string in the given locale.
     *
     * If no locale is given, then the locale of the current context will be used.
     *
     * @param string|null $locale
     *
     * @throws \InvalidArgumentException The given locale was not defined.
     * @return string
     */
    public function get($locale = null)
    {
        if ($locale === null) {
            $locale = $this->context->getLocale();
        }

        $language = Util::getLanguage($locale);

        if (! property_exists($this, $language)) {
            throw new \InvalidArgumentException(sprintf('There is no locale "%s" defined', $locale));
        }

        return $this->{$language};
    }
}
