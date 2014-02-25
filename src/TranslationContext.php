<?php

namespace Mnapoli\Translated;

/**
 * Represents a context for translations.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslationContext
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string[]
     */
    private $fallback;

    /**
     * @param string   $locale   Current locale, for example from the request, or from the logged in user.
     * @param string[] $fallback List of fallback locales.
     */
    public function __construct($locale, array $fallback = [])
    {
        $this->locale = $locale;
        $this->fallback = $fallback;
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string[]
     */
    public function getFallback()
    {
        return $this->fallback;
    }
}
